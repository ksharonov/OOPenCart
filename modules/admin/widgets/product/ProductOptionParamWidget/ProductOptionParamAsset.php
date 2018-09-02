<?php

namespace app\modules\admin\widgets\product\ProductOptionParamWidget;

use yii\web\AssetBundle;

class ProductOptionParamAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = '@app/modules/admin/widgets/product/ProductOptionParamWidget';

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}