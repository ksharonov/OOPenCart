<?php

namespace app\modules\cart\widgets\ElementCartWidget;

use yii\web\AssetBundle;

class ElementCartAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/cart/widgets/ElementCartWidget';

    public $css = [

    ];
    public $js = [

    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}