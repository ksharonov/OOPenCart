<?php

namespace app\modules\admin\widgets\product\ProductImageWidget;

use yii\web\AssetBundle;

class ProductImageAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductImageWidget';

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
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}