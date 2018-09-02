<?php

namespace app\modules\order\assets\PrintAsset;

use yii\web\AssetBundle;

class PrintAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/order/assets/PrintAsset';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/script.js',
    ];

    public $css = [
        'css/main.css',
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}