<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 11-01-2018
 * Time: 15:07 PM
 */

namespace app\extensions\payment\TestPaymentExtension;

use app\models\db\Order;
use app\system\interfaces\IPaymentExtension;
use yii\base\Widget;
use app\system\extension\PaymentExtension;

/**
 * Class TestPaymentExtension
 * Тестовый виджет оплаты
 * @package app\extensions\payment\TestPaymentExtension
 */
class TestPaymentExtension extends PaymentExtension implements IPaymentExtension
{

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->_view, [
            'fields' => $this->fields,
            'id' => $this->id,
            'extensionParams' => $this->extensionParams,
            'order' => $this->order
        ]);
    }

}