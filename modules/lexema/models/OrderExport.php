<?php

namespace app\modules\lexema\models;

use app\helpers\ModelRelationHelper;
use app\models\db\Client;
use app\models\db\Order;
use app\models\db\LexemaOrder;
use app\models\db\OrderStatus;
use app\models\db\OrderStatusHistory;
use app\models\db\OuterRel;
use app\models\db\Setting;
use app\models\db\Storage;
use app\modules\lexema\api\Connection;
use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\connection\HttpConnection;
use app\modules\lexema\api\db\LexemaApiOrder;
use app\modules\lexema\api\repository\OrderRepository;
use yii\base\BaseObject;
use yii\helpers\Json;

class OrderExport extends BaseObject implements \app\system\interfaces\IOrderExport
{

    public $errorMsg = null;

    public function prepareOrder(\app\models\db\Order &$order): bool
    {
        return true;
    }

    /**
     * Отправка заказа
     * @param Order $order
     * @return bool
     * @throws \yii\base\Exception
     */
    public function sendOrder(\app\models\db\Order &$order): bool
    {
        //----- init
        $order->refresh();
        $deliveryData = $order->deliveryData;                 // данные доставки
        $userData = $order->userData;                     // данные пользователя
        $paymentData = $order->payment->extension;           // данные по оплате
        $cost = array_values($order->addCosts);       // стоимость доставки
        $method = $order->delivery->extension->title;   // способ доставки

        if ($order->cardData) {
            if ($order->cardData['use']) {
                $card = $order->cardData['number'];
                $cardBonuses = $order->cardData['value'];
            }
        }

        if (isset($deliveryData['transportCompany'])) {        // транспортная компания
            $method = $method . ": "
                . $deliveryData['transportCompany'];
        }

        if (isset($deliveryData['city'])) {                     // адрес доставки
            $address = $deliveryData['city'] . ", ул. "
                . $deliveryData['street'] . ", д. "
                . $deliveryData['home'] . ", кв. "
                . $deliveryData['room'];
        }

        //----------

        $xml = new \DOMDocument();
        //$xml->formatOutput = true;
        //$xml->encoding = 'UTF-8';

        $root = $xml->createElement('Document');
        $root = $xml->appendChild($root);

        $lexemaOrderId = $order->id . Setting::get('LEXEMA.ORDER.PREFIX');

        $orderId = $xml->createElement('OrderId', $lexemaOrderId);
        $orderId = $root->appendChild($orderId);

        $orderDateTime = $xml->createElement('OrderDateTime', $order->dtCreate);
        $orderDateTime = $root->appendChild($orderDateTime);

        // TODO тут для физиков должен быть статичный гуид
        $agentGuid = $order->client->guid ?? null;//"AC643C5A-7D3D-4769-9F6C-E2607BB5BBAA"; //"AC643C5A-7D3D-4769-9F6C-E2607BB5BBAA";
        $agentId = $xml->createElement('AgentId', $agentGuid);
        $agentId = $root->appendChild($agentId);


        if (isset($deliveryData['storageId'])) {
            $storage = Storage::findOne($deliveryData['storageId']);
        }
        $storageGuid = $storage->guid ?? null;
        $storageId = $xml->createElement('StorageId', $storageGuid);
        $storageId = $root->appendChild($storageId);

        $user = $xml->createElement('User');
        $user = $root->appendChild($user);
        $user->appendChild($xml->createElement('Login', $userData['phone'] ?? $userData['username'] ?? null));
        $user->appendChild($xml->createElement('FIO', $userData['fio'] ?? $userData['name'] ?? null));
        $user->appendChild($xml->createElement('Email', $userData['email'] ?? $order->user->email ?? null));

        $loyalCard = $xml->createElement('LoyalCardNumber', $card ?? null);
        $loyalCard = $root->appendChild($loyalCard);

        $bonuses = $xml->createElement('LoyalCardNumber', $cardBonuses ?? null);
        $bonuses = $root->appendChild($bonuses);

        // TODO этого еще нет
        $onlinePaymentInfo = $xml->createElement('OnlinePaymentInfo');
        $onlinePaymentInfo = $root->appendChild($onlinePaymentInfo);
        $onlinePaymentInfo->appendChild($xml->createElement('Operator', null));
        $onlinePaymentInfo->appendChild($xml->createElement('TransactionId', null));
        $onlinePaymentInfo->appendChild($xml->createElement('PaymentAmount', null));
        $onlinePaymentInfo->appendChild($xml->createElement('TransactionDateTime', null));
        $onlinePaymentInfo->appendChild($xml->createElement('Information', null));


        // Раздел доставки :
        $shippingTerms = $xml->createElement('ShippingTerms');
        $shippingTerms = $root->appendChild($shippingTerms);
        $shippingTerms->appendChild($xml->createElement('DeliveryMethod', $method ?? 'Тут способ доставки'));
        $shippingTerms->appendChild($xml->createElement('DeliveryAddress', $address ?? null));
        $shippingTerms->appendChild($xml->createElement('DeliveryCost', $cost[0] ?? null));
        $shippingTerms->appendChild($xml->createElement('ContactPerson', $userData['fio'] ?? $userData['name'] ?? null));
        $shippingTerms->appendChild($xml->createElement('ContactPhone', $userData['phone'] ?? null));
        $shippingTerms->appendChild($xml->createElement('Comment', $deliveryData['comment'] ?? null));

        $buyerComment = $xml->createElement('BuyerCommentary', $order->comment ?? 'Комментариев нет');
        $buyerComment = $root->appendChild($buyerComment);

        $managerComment = $xml->createElement('ManagerCommentary', 'Комментариев нет');
        $managerComment = $root->appendChild($managerComment);

        $itemTable = $xml->createElement('Items');
        $itemTable = $root->appendChild($itemTable);

        $items = $order->content;

        $sumBNDS = 0;
        $vat = 0;
        $sumNDS = 0;

        foreach ($items as $item) {
            $itemNode = $xml->createElement('Item');
            $itemNode = $itemTable->appendChild($itemNode);

            $balance = $item->product->balance ?? null;

            $itemNode->appendChild($xml->createElement('ProductId', $item->product->guid));
            $itemNode->appendChild($xml->createElement('EdizmId', $item->product->units[0]->guid ?? null));
            $itemNode->appendChild($xml->createElement('Amount', +$item->count));
            $itemNode->appendChild($xml->createElement('Price', $item->priceValue));
            $itemNode->appendChild($xml->createElement('SumBNds', $item->sumWVat));
            $itemNode->appendChild($xml->createElement('NDS', $item->vat));
            $itemNode->appendChild($xml->createElement('SumNDS', $item->sum));
            $itemNode->appendChild($xml->createElement('DiscountSum', null)); // TODO нету
            $itemNode->appendChild($xml->createElement('DeliveryDate', null)); // TODO нету
            $itemNode->appendChild($xml->createElement('Balance', $balance));

            $sumBNDS += $item->sumWVat;
            $vat += $item->vat;
            $sumNDS += $item->sum;
        }

        // Дополнительная нода с доп. ценой
        if ($cost[0] && $cost[0] > 0) {
            $extraItemNode = $xml->createElement('Item');
            $extraItemNode = $itemTable->appendChild($extraItemNode);

            $extraItemNode->appendChild($xml->createElement('ProductId', 'Доставка'));
            $extraItemNode->appendChild($xml->createElement('EdizmId', null));
            $extraItemNode->appendChild($xml->createElement('Amount', null));
            $extraItemNode->appendChild($xml->createElement('Price', $cost[0]));
            $extraItemNode->appendChild($xml->createElement('SumBNds', null));
            $extraItemNode->appendChild($xml->createElement('NDS', null));
            $extraItemNode->appendChild($xml->createElement('SumNDS', null));
            $extraItemNode->appendChild($xml->createElement('DiscountSum', null)); // TODO нету
            $extraItemNode->appendChild($xml->createElement('DeliveryDate', null)); // TODO нету
            $extraItemNode->appendChild($xml->createElement('Balance', null));
        }

        $xmlString = $xml->saveXML();

        $params = [
            "CHost" => null,
            "CUser" => null,
            "WHost" => null,
            "name" => null,
            "CDate" => null,
            "WDate" => null,
            "Tdoc" => "EZK",
            "wuser" => null,
            "wdate" => null,
            "cuser" => null,
            "cdate" => null,
            "GlobalIdDeban" => "AC643C5A-7D3D-4769-9F6C-E2607BB5BBAA",
            "GlobalIdKredan" => "79bc8fba-f1c5-11df-8e48-00265a79ac22",
            "Bdate" => null,
            "Rdate" => date("Y.m.d"), //"2018.02.15",
            "Edate" => null,
            "Note" => date("Y.m.d"),
            "Items" => $xmlString,
        ];

        if (\Yii::$app->user->isGuest) {
            $params['GuestLogin'] = 1;
        } else {
            $params['GuestLogin'] = 0;
        }

        $lexemaOrder = new LexemaOrder();
        $lexemaOrder->orderNumber = $lexemaOrderId;
        $lexemaOrder->orderId = $order->id;
        $lexemaOrder->save();

        //$connect = new \app\modules\lexema\models\LexemaConnect();
        //$connection = HttpConnection::get();
        $connection = CookieAuthConnection::get();

        $result = false;

        try {
            $result = $connection->send('ESI_GetReestrEZK', $params);
            //$connect->getToken();
            //$result = $connect->sendOrder($params);
        } catch (\Exception $e) {
            // TODO действия в случае ошибки отправки заказа в Лексему
            $errorOrderStatusId = Setting::get('ORDER.STATUS.ERROR');
            $order->setOrderStatus($errorOrderStatusId);

            file_put_contents(\Yii::getAlias('@app') . '/runtime/logs/order_error.log', $e->getMessage());
            file_put_contents(\Yii::getAlias('@app') . '/runtime/logs/order.log',
                array(print_r($result, true), print_r($params, true)));

        }

        // действия в случае успеха отправки
        if ($result) {
            $successOrderStatusId = Setting::get('ORDER.STATUS.SEND');
            $order->setOrderStatus($successOrderStatusId);
            file_put_contents(\Yii::getAlias('@app') . '/runtime/logs/order.log',
                array(print_r($result, true), print_r($params, true)));

            // сразу получаем доки (не, фигня)
            // получаем vcode

            $apiOrder = OrderRepository::get()
                ->find(['OrderId' => $lexemaOrderId])
                ->one();

            $lexemaOrder->vcode = $apiOrder['vcode'];
            $lexemaOrder->save();
        }


        return (bool)$result;
    }

    /**
     * Запрос на отмену заказа
     * @param Order $order
     * @return bool
     * @throws \Exception
     */
    public function sendCancel(Order $order)
    {
        return $this->updateOrder($order, ['StatusDoc' => LexemaOrder::CANCEL_CODE]);
    }

    /**
     * Запрос на обновление информации о заказе
     * todo стоило бы добавить наверное в интерфейс этот updateOrder
     * @param Order $order
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function updateOrder(Order $order, array $params = [])
    {
        $connection = CookieAuthConnection::get();
        $result = false;
        $params['Vcode'] = $order->vcode;

        if ($order->vcode) {
            $result = $connection->send('ESI_GetReestrEZK', $params, 'PUT');
        }

//        dump($params);
//        dump($result);
        return $result;
    }
}