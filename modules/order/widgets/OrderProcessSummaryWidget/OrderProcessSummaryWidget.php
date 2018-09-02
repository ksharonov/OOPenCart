<?php

namespace app\modules\order\widgets\OrderProcessSummaryWidget;

use app\components\CartComponent;
use app\models\base\order\OrderProcess;
use app\models\db\Order;
use app\models\session\OrderSession;
use yii\base\Widget;

/**
 * Class OrderProcessSummaryWidget
 *
 * Виджет отображания итоговых данных в оформлении заказа
 *
 * @package app\modules\order\widgets\OrderProcessSummaryWidget
 */
class OrderProcessSummaryWidget extends Widget
{
    /** @var OrderProcess */
    public $process = null;

    /** @var Order | OrderSession */
    public $order = null;

    public function run()
    {
        $order = OrderSession::get();

        /** @var CartComponent $cart */
        $cartComponent = \Yii::$app->cart;
        return $this->render('index', [
            'order' => $order,
            'process' => $this->process,
            'cart' => $cartComponent->get()
        ]);
    }
}