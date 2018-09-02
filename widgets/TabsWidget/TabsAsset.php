<?php

namespace app\widgets\TabsWidget;

use yii\web\AssetBundle;

class TabsAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/TabsWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
//        'yii\bootstrap\BootstrapAsset',
    ];
}