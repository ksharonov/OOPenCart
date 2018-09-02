<?php

namespace app\models\base\order;

use app\models\db\OrderContent;
use app\models\db\Setting;
use app\modules\lexema\models\OrderExport;
use yii\base\BaseObject;
use app\models\db\Order;
use app\models\session\OrderSession;
use yii\db\Exception;

/**
 * Class OrderActions
 *
 * Действия над заказом
 *
 * @package app\models\base\order
 * @property Order $order
 */
class OrderActions extends BaseObject
{
    /** @var Order | OrderSession */
    private $_order;

    /**
     * OrderDelivery constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param Order | OrderSession $order
     * @throws Exception
     */
    public function setOrder($order)
    {
        if ($order instanceof Order || $order instanceof OrderSession) {
            $this->_order = $order;
        } else {
            throw new Exception("Объект не является экземпляром класса Order или OrderSession");
        }
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }


    /**
     * Копирование заказа
     */
    public function copy()
    {
        $orderContent = $this->order->content;

        $orderNew = new Order();
        $orderNew->setAttributes($this->order->attributes);
        $orderNew->dtCreate = null;
        $orderNew->dtUpdate = null;
        $result = $orderNew->save();

        if ($result) {
            foreach ($orderContent as $content) {
                $orderContentNew = new OrderContent();
                $orderContentNew->setAttributes($content->attributes);
                $orderContentNew->orderId = $orderNew->id;
                $orderContentNew->save();
            }
        }

        return $orderNew;
    }

    /**
     * Отмена заказа
     * @return bool
     */
    public function cancel()
    {
        $export = new OrderExport();
        $result = $export->sendCancel($this->order);

        if ($result) {
            $this->order->setOrderStatus(Setting::get('ORDER.STATUS.CANCELED'));
        }

        return $result;
    }
}