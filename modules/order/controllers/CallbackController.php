<?php

namespace app\modules\order\controllers;

use app\components\CartComponent;
use app\helpers\MailHelper;
use app\modules\lexema\models\OrderExport;
use app\system\base\Controller;
use app\models\db\Order;
use app\models\db\Setting;
use app\models\db\Extension;
use app\system\extension\PaymentExtension;
use app\models\session\OrderSession;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * Class CallbackController
 *
 * Контроллер для действий колбэка систем оплаты/подтверждения успешности оплаты
 *
 * @package app\modules\order\controllers
 */
class CallbackController extends Controller
{

    /**
     * Действия выполняемые после оплаты
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $result = false;
        $deliveryCode = false;

        $orderSession = OrderSession::get();
        $orderExport = new OrderExport();
//        if (!isset($orderSession->order) || !isset($orderSession->id)) {
//            return $this->redirect('/');
//        }

        $order = $orderSession->order;

        /** @var PaymentExtension $ext */
        $ext = $order->payment->extensionInstance;
        if ($ext && method_exists($ext, 'afterPaid')) {
            $result = $ext::afterPaid();
        }

        if ($result && !$orderSession->error) {

            /** @var CartComponent $cart */
            $cart = \Yii::$app->cart;
            $cart->clear();

            $orderStatusPaid = Setting::get('ORDER.STATUS.PAID');
            $order->orderStatus = $orderStatusPaid ?? null;

            if (Setting::get('LEXEMA.ALLOW.SEND') && $order->sberbank) {
                $FD = $order->cheque->fd ?? null;
                $FN = $order->cheque->fn ?? null;
                $deliveryCode = rand(100000, 999999);

                $orderExport->updateOrder($order, [
                    'TransactionDateTime' => $order->cheque->dtc ?? null,
                    'Information' => "ФН: {$FN}; ФД: {$FD}; Код доставки: {$deliveryCode}",
                    'TransactionId' => $order->sberbank->sberbankOrderId ?? true,
                    'PaymentAmount' => $order->finalSum
                ]);
            }

        } else {
            $orderStatusPaid = Setting::get('ORDER.STATUS.PAID.ERROR');
            $order->orderStatus = $orderStatusPaid ?? null;
        }

        $order->deliveryCode = $deliveryCode;
        $order->save();
        MailHelper::newOrder($order);
        return $this->redirect('/order/process/final');
    }

    /**
     * Д
     * @return \yii\web\Response
     */
    public function actionUpdate()
    {
        sleep(2);
        $result = false;

        $orderSession = OrderSession::get();
        $order = $orderSession->order;
        $ext = $order->payment->extensionInstance;
        $order->paymentMethod = Extension::findOne(['name' => 'sberbank_payment_widget'])->id;
        $order->save();
        if ($ext && method_exists($ext, 'afterPaid')) {
            $result = $ext::afterPaid();
        }

        if ($result) {
            $orderStatusPaid = Setting::get('ORDER.STATUS.PAID');
            $order->orderStatus = $orderStatusPaid ?? null;
            OrderSession::deleteAll();
            return $this->redirect('/profile/');
        }
    }
}
