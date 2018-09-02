<?php

namespace app\modules\admin\widgets\product\ProductPriceWidget;

use yii\web\AssetBundle;

class ProductPriceAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductPriceWidget';

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
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}