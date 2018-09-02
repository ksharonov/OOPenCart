<?php

namespace app\widgets\ReCallWidget;

use yii\web\AssetBundle;

class ReCallAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/ReCallWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [

    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}