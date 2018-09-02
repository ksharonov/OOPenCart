<?php

namespace app\models\traits;

use app\models\base\order\OrderDelivery;
use app\models\base\order\OrderPayment;

/**
 * Class OrderTrait
 *
 * Общий функционал заказов
 *
 */
trait OrderTrait
{
    /**
     * @return OrderDelivery
     */
    public function getDelivery()
    {
        $orderDelivery = new OrderDelivery();
        $orderDelivery->order = $this;
        return $orderDelivery;
    }

    /**
     * Данные по оплате
     * @return OrderPayment
     */
    public function getPayment()
    {
        $orderPayment = new OrderPayment();
        $orderPayment->order = $this;
        return $orderPayment;
    }
}