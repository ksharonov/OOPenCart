<?php

namespace app\modules\admin\widgets\product\ProductOptionValueSelectorWidget;

use yii\web\AssetBundle;

class ProductOptionValueSelectorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = '@app/modules/admin/widgets/product/ProductOptionValueSelectorWidget';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}