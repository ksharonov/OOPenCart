<?php

namespace app\modules\order\assets\ProcessAsset;

use yii\web\AssetBundle;

class ProcessAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/order/assets/ProcessAsset';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/print.js',
        'js/script.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}