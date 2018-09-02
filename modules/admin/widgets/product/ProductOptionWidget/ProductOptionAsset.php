<?php

namespace app\modules\admin\widgets\product\ProductOptionWidget;

use yii\web\AssetBundle;

class ProductOptionAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = '@app/modules/admin/widgets/product/ProductOptionWidget';
    public $js = [
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}