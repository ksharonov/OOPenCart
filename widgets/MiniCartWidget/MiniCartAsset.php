<?php

namespace app\widgets\MiniCartWidget;

use yii\web\AssetBundle;

class MiniCartAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/MiniCartWidget';

    public $css = [

    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}