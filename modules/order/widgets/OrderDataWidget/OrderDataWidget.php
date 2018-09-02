<?php

namespace app\modules\order\widgets\OrderDataWidget;

use yii\base\Widget;
use app\modules\order\widgets\OrderDataWidget\OrderDataAsset;

class OrderDataWidget extends Widget
{
    public $order = null;

    public function run()
    {
        $view = $this->getView();
        OrderDataAsset::register($view);

        return $this->render('index', [
            'order' => $this->order
        ]);
    }
}