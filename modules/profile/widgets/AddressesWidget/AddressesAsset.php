<?php

namespace app\modules\profile\widgets\AddressesWidget;

use yii\web\AssetBundle;

class AddressesAsset extends AssetBundle
{
    public $sourcePath = '@profile/widgets/AddressesWidget';

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