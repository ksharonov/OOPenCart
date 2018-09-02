<?php

namespace app\system\extension;

use app\models\db\Order;
use app\system\Extension;
use \Exception;
use yii\widgets\ActiveForm;

/**
 * Class DeliveryExtension
 * Класс расширения доставки заказа
 *
 * @package app\system\extension
 */
class DeliveryExtension extends Extension
{
    /**
     * @var
     */
    public $id;

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

    /**
     * @throws Exception
     */
    public function init()
    {
//        if (!$this->order) {
//            throw new Exception('Отсутствие заказа');
//        }

        if (isset($this->order->deliveryData)) {
            $this->fields = $this->order->deliveryData;
        }

        parent::init();
    }

    /**
     * Действия после установки данных доставки
     * @inheritdoc
     */
    public static function afterSet(array $params = [])
    {
        dump(1);
    }
}