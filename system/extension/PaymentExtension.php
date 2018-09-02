<?php

namespace app\system\extension;

use app\models\db\Order;
use app\system\Extension;
use app\system\interfaces\IPaymentExtension;
use yii\db\Exception;
use yii\widgets\ActiveForm;

/**
 * Class PaymentExtension
 * Класс расширения оп заказа
 *
 * @package app\system\extension
 */
class PaymentExtension extends Extension implements IPaymentExtension
{
    /**
     * @var Order
     */
    public $order;

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var array параметры виджета по умолчанию
     */
    public $_defaultParams = [
        'username' => '',
        'login' => '',
        'paymentUrl' => '',
        'token' => ''
    ];

    /**
     * @var array набор полей по умолчанию, которые нужны для данного виджета
     * на этапе настройки
     * Пока тестовый, поэтому структура полей в рассмотрении
     */
    public $fields = [
        'test1' => null,
        'test2' => null
    ];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        if (!$this->order) {
            throw new Exception('Отсутствие заказа');
        }

        if ($this->order->paymentData) {
            $this->fields = $this->order->paymentData;
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function beforePaid(array $params = [])
    {
        return (object)[
            'orderId' => null,
            'formUrl' => null
        ];
    }

    /**
     * @inheritdoc
     */
    public static function afterPaid(array $params = [])
    {
        return false;
    }
}