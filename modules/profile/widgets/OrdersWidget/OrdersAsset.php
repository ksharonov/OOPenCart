<?php

namespace app\modules\profile\widgets\OrdersWidget;

use yii\web\AssetBundle;

class OrdersAsset extends AssetBundle
{
    public $sourcePath = '@profile/widgets/OrdersWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [

    ];
    public $js = [
        'js/script.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}