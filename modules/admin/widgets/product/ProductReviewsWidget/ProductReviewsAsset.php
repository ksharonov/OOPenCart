<?php

namespace app\modules\admin\widgets\product\ProductReviewsWidget;

use yii\web\AssetBundle;

class ProductReviewsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductReviewsWidget';

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