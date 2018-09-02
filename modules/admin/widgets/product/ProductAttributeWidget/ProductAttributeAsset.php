<?php

namespace app\modules\admin\widgets\product\ProductAttributeWidget;

use yii\web\AssetBundle;

class ProductAttributeAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductAttributeWidget';

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