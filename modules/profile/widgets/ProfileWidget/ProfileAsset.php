<?php

namespace app\modules\profile\widgets\ProfileWidget;

use yii\web\AssetBundle;

class ProfileAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/profile/widgets/ProfileWidget';

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