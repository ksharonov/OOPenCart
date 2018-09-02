<?php

namespace app\modules\admin\widgets\product\ProductAnalogueWidget;

use yii\web\AssetBundle;

class ProductAnalogueAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductAnalogueWidget';

    public $css = [
        'css/style.css'
    ];
    public $js = [
        'js/script.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}