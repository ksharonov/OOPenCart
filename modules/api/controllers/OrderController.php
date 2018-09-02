<?php

namespace app\modules\api\controllers;

use app\models\db\Order;
use app\models\db\OrderContent;
use app\models\db\Sms;
use Yii;
use app\system\base\ApiController;

/**
 * Class OrderController
 *
 * Действия над заказом
 *
 * @package app\modules\api\controllers
 */
class OrderController extends ApiController
{
    public function actionIndex()
    {

    }

    /**
     * Копирование заказа
     * @return bool
     */
    public function actionCopy()
    {
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $orderId = $params['id'];
            if (is_array($orderId)) {
                foreach ($orderId as $id) {
                    $this->orderCopy($id);
                }
            } else {
                $this->orderCopy($orderId);
            }
            return true;
        }
        return false;
    }

    /**
     * Установить статус
     */
    public function actionStatus()
    {

    }

    /**
     * Сформировать счёт по оплате
     */
    public function actionInvoiceForPayment()
    {

    }

    /**
     * Отправка СМС при оформлении
     */
    public function actionSendProcessSms()
    {
        $number = Yii::$app->request->post('number');
        $lexemaCard = Yii::$app->user->identity->getLexemaCard($number) ?? Yii::$app->user->identity->getLexemaDiscountCard($number);
        if ($lexemaCard) {
            Sms::setCodeToPhone($lexemaCard->phone);
            return true;
        }

        return false;
    }

    /**
     * Подтверждение СМС при оформлении
     */
    public function actionSubmitProcessSms()
    {
        $code = \Yii::$app->request->post('code') ?? null;
        $number = Yii::$app->request->post('number') ?? null;
//        var_dump($code);
//        var_dump($number);
        $lexemaCard = Yii::$app->user->identity->getLexemaCard($number) ?? Yii::$app->user->identity->getLexemaDiscountCard($number) ?? false;
//        var_dump($lexemaCard);
        if ($lexemaCard) {
            $sms = Sms::findLastSmsByNumber($lexemaCard->phone);
            $sms->attempts++;

            if ($sms && $sms->code == $code) {
                $sms->success = Sms::IS_SUCCESS;
            }

            $sms->save();

            if ($sms->success) {
                return true;
            }
        }

        return false;
    }

    /**
     * Отменить заказ
     */
    public function actionCancel()
    {
        $user = Yii::$app->user->identity->id ?? null;

        if (!$user) {
            return false;
        }

        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $orderId = $params['id'];
            if (is_array($orderId)) {
                foreach ($orderId as $id) {
                    $this->orderCancel($id);
                }
            } else {
                $this->orderCancel($orderId);
            }
        }
    }


    //todo вообще это всё ниже надо в модель кинуть, но какую?))

    /**
     * Копирование заказа
     * @param $orderId
     * @return bool|Order
     */
    public function orderCopy($orderId)
    {
        $user = Yii::$app->user->identity->id ?? null;

        if (!$user) {
            return false;
        }

        $order = Order::findOne(['id' => $orderId, 'userId' => $user->id]);

        if (!$order) {
            return false;
        }

        $result = $order->action->copy();
        return $result;
    }

    /**
     * Отменить заказ
     */
    public function orderCancel($orderId)
    {
        $order = Order::findOne(['id' => $orderId]);
        $result = $order->action->cancel();
        return $result;
    }
}