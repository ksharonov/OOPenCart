<?php

namespace app\modules\order\controllers;

use app\helpers\MailHelper;
use app\models\base\Mailer;
use app\models\db\Sms;
use app\models\OneC\OrderClientOneC;
use app\modules\lexema\models\OrderExport;
use Yii;
use app\components\CartComponent;
use app\models\base\Cart;
use app\models\db\Extension;
use app\system\extension\PaymentExtension;
use app\system\base\Controller;
use app\models\db\Order;
use app\models\db\Setting;
use app\models\session\OrderSession;
use app\models\db\User;
use app\models\base\order\OrderSubmit;
use app\models\base\order\OrderProcess;
use yii\base\Exception;

/**
 * Class SubmitController
 *
 * Контроллер действий подтверждения заказа
 *
 * @package app\modules\order\controllers
 */
class SubmitController extends Controller
{
    /**
     * Подтверждение оплаты
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $orderId = null;
        $formUrl = null;

        /** @var CartComponent $cart */
        $cart = \Yii::$app->cart;
        $preOrder = OrderSession::get();
//        dump($preOrder);
        if (!$preOrder) { //$cart->isNull() ||
            return $this->redirect('/');
        }

        if (Yii::$app->user->identity) {
//            $phone = $preOrder->cardData['number'];
//            $sms = Sms::findLastSmsByNumber(Yii::$app->user->identity);
//
//            if (Yii::$app->user->identity->lexemaCard && !$sms->success) {
//                $preOrder->cardData = null;
//                $preOrder->save();
//            }

        }

        $orderSubmit = new OrderSubmit();
        $orderSubmit->prepare();

        /** @var Order $order */
        $order = $orderSubmit->save();
//        $cart->clear();
        /** @var PaymentExtension $ext */
        $ext = $order->payment->extensionInstance;

        if ($ext && method_exists($ext, 'beforePaid')) {
            $params = $ext::beforePaid();
            $orderId = $params->orderId ?? null;
            $formUrl = $params->formUrl ?? null;
        }

//        Mailer::sendToAdmin([
//            'email' => 'test@test.com',
//            'name' => 'Заказ',
//            'subject' => 'Новый заказ на сайте',
//            'body' => 'На сайте оформили новый заказ: №' . $order->id
//        ]);

        // экспортируем заказ в Лексему
        // в случае ошибки - добавил свойство $error в OrderSession
        // можно выводить ее во вьюхах
        //$orderCLientOneC = new OrderClientOneC();
        //$orderCLientOneC->sendOrderClientOneC();


        if (Setting::get('ONEC.ALLOW.SEND') == '1') {
            $orderOneC = new OrderClientOneC();
//            dump($order);
            $result = $orderOneC->sendOrderClientOneC($order);
//            dump($result);
//            exit;
            if ($result !== true) {
                //$preOrder->error = $orderExport->errorMsg;
                //$preOrder->save();
            }
        }

        if (Setting::get('LEXEMA.ALLOW.SEND') == '1') {
            $orderExport = new OrderExport();
            $result = $orderExport->sendOrder($order);

            if ($result !== true) {
                $preOrder->error = $orderExport->errorMsg;
                $preOrder->save();
            }
        }

        $orderStatusProcess = Setting::get('ORDER.STATUS.PROCESS');

        if ($order->lastStatus->id != $orderStatusProcess) {
            $order->orderStatus = $orderStatusProcess;
        }

//        dump("Делаем действия перед редиректом на систему оплаты");
        if ($formUrl) {
            return $this->redirect($formUrl);
        } else {
            return $this->redirect('/order/callback/');
        }

    }

    /**
     * Дооплата заказа через сбер
     * @param $id
     * @return \yii\web\Response
     */
    public function actionPay(int $id = null)
    {
        if (is_null($id) || is_string($id)) {
            return $this->redirect('/profile/');
        }

        $order = Order::find()
            ->where([
                'id' => $id,
                'clientId' => Yii::$app->client->get()->id
            ])->one();

        if (!$order) {
            return $this->redirect('/profile/');
        }

        $orderSession = OrderSession::get();
        $orderSession->setAttribute('id', $order->id);
        $orderSession->save();

        $order = $orderSession->order;
        $order->paymentMethod = Extension::findOne(['name' => 'sberbank_payment_widget'])->id;

//        $cart->clear();
        /** @var PaymentExtension $ext */
        $ext = $order->payment->extensionInstance;


        if ($ext && method_exists($ext, 'beforePaid')) {
            $params = $ext::beforePaid([
                'callback' => '/order/callback/update',
                'finalSum' => $order->finalSum
            ]);
            $formUrl = $params->formUrl ?? null;

            if ($formUrl) {
                return $this->redirect($formUrl);
            }
        }

        return $this->redirect('/profile/');
//        dump($orderSession);
//        return $this->redirect('/order/submit/');
    }
}