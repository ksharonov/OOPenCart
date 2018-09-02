<?php

namespace app\models\base\order;

use yii\base\BaseObject;
use app\models\db\Order;
use app\models\session\OrderSession;
use yii\db\Exception;
use app\models\db\Extension;

/**
 * Class OrderPayment
 * @package app\models\base\order
 *
 * @property Order $order
 */
class OrderPayment extends BaseObject
{
    const PAYMENT_METHOD_SHOP = 0;
    const PAYMENT_METHOD_EXTENSION = 1;
    const PAYMENT_METHOD_OTHER = 2;

    /**
     * @var array массив систем оплаты
     */
    public static $methods = [
        self::PAYMENT_METHOD_SHOP => 'Оплата в магазине',
        self::PAYMENT_METHOD_EXTENSION => 'Онлайн',
        self::PAYMENT_METHOD_OTHER => 'Безналичный платеж'
    ];

    public static $terminalMethods = [
        self::PAYMENT_METHOD_SHOP => 'Оплата на кассе',
        self::PAYMENT_METHOD_EXTENSION => 'Онлайн',
    ];

    /** @var Order | OrderSession */
    private $_order;

    /**
     * OrderPayment constructor.
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
     * Название метода оплаты
     * @return mixed
     */
    public function getTitle()
    {
        return self::$methods[$this->_order->paymentMethod] ?? null;
    }

    /**
     * Данные по оплате
     */
    public function getData()
    {
        return (object)$this->_order->paymentData;
    }

    /**
     * @return null|string
     */
    public function getExtension()
    {
        $extId = $this->_order->paymentMethod;
        $extension = Extension::get($extId) ?? null;
        return $extension ?? null;
    }

    /**
     * Получение инстанса расширения оплаты
     * @return null
     */
    public function getExtensionInstance()
    {
        if (!$this->extension) {
            return null;
        }

        $ext = $this->extension::getInstance([
            'order' => $this->_order
        ]);

        return $ext;
    }
}