<?php

namespace app\modules\profile\widgets\AccessWidget;

use yii\web\AssetBundle;

class AccessAsset extends AssetBundle
{
    public $sourcePath = '@profile/widgets/AccessWidget';

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