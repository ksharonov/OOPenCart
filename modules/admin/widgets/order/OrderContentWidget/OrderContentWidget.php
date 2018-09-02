<?php

namespace app\modules\admin\widgets\order\OrderContentWidget;

use app\models\db\Order;
use yii\base\Widget;

/**
 * Виджет списка товаров в заказе
 *
 * @property Order $model
 */
class OrderContentWidget extends Widget
{
    public function run()
    {
        return $this->render('index');
    }
}