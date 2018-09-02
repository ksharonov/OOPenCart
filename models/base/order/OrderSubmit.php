<?php

namespace app\models\base\order;

use Yii;
use app\components\CartComponent;
use app\models\session\OrderSession;
use app\models\db\User;
use yii\base\BaseObject;
use app\models\db\Order;
use app\models\base\Cart;


/**
 * Class OrderSubmit
 *
 * Класс подтверждения и создания заказа
 *
 * @package app\models\base\order
 */
class OrderSubmit extends BaseObject
{
    /**
     * @var OrderSession
     */
    private $_order;

    /**
     * @var Cart
     */
    private $_cart;

    /**
     * @var User
     */
    private $_user;


    /**
     * Подготовка заказа
     */
    public function prepare()
    {
        $this->_order = OrderSession::copy();
//        $this->_order = new OrderSession();
//        $this->_order->sessionId = 'submit';
//
//        foreach ($orderSession->model->attributes as $attribute => $value) {
//            $this->_order->$attribute = $value;
//        }

        /** @var CartComponent $cart */
        $cart = \Yii::$app->cart;
        $this->_cart = $cart->get();


        $this->prepareDelivery();
        $this->preparePayment();
        $this->prepareUser();

        $this->_order->save();
    }

    /**
     * Сохранение заказа
     */
    public function save()
    {
        //todo оттестить?
        if (isset($this->_order->id)){
            $order = Order::findOne(['id' => $this->_order->id]);
        } else {
            $order = new Order();
        }
        $order->setAttributes($this->_order->model->attributes);
        $order->setParamAttributes($this->_order->paramAttributes);
//        dump($order->param->getAsArray());
        // todo тут ли это должно быть?
        if (Yii::$app->setting->get('TERMINAL.STATUS') && Yii::$app->user->isGuest)
            $order->clientId = Yii::$app->setting->get('TERMINAL.CLIENT');

        $order->content = $this->_cart;
        $order->user = $this->_user;
//        dump($order);
//        exit;
        if ($order->save()) {

            $orderSession = OrderSession::get();
            $orderSession->id = $order->id;
            $orderSession->save();
            $this->_order->delete();
            return $order;
        }

        return null;
    }

    /**
     * Подготовка данных доставки
     */
    private function prepareDelivery()
    {
        $this->_order->deliveryData = $this->_order->deliveryData[(int)$this->_order->deliveryMethod] ?? null;
    }

    /**
     * Подготовка данных оплаты
     */
    private function preparePayment()
    {
        $this->_order->paymentData = $this->_order->paymentData[(int)$this->_order->paymentMethod] ?? null;
    }

    /**
     * Подготовка данных пользователя
     */
    private function prepareUser()
    {
        $this->_order->userData = $this->_order->userData[(int)$this->_order->clientType] ?? null;
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $this->_user = $user;
    }
}