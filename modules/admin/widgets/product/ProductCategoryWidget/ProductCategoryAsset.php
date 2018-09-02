<?php

namespace app\modules\admin\widgets\product\ProductCategoryWidget;

use yii\web\AssetBundle;

class ProductCategoryAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductCategoryWidget';

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