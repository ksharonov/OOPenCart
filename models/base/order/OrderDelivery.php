<?php

namespace app\models\base\order;

use app\models\db\Extension;
use app\models\db\Order;
use app\models\session\OrderSession;
use app\system\extension\DeliveryExtension;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class OrderDelivery
 *
 * Класс получения информации по доставке из заказа
 *
 * @package app\models\base\order
 * @property Order $order
 * @property DeliveryExtension $extension
 */
class OrderDelivery extends BaseObject
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
     * @return mixed
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Получить расширение доставки
     * @return Extension | null
     */
    public function getExtension()
    {
        $extId = $this->_order->deliveryMethod ?? null;
        $extension = Extension::get($extId) ?? null;

        if (!$extension){
            return null;
        }

        return new $extension() ?? null;
    }
}