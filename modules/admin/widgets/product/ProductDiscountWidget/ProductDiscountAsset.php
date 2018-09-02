<?php

namespace app\modules\admin\widgets\product\ProductDiscountWidget;

use yii\web\AssetBundle;

class ProductDiscountAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductDiscountWidget';


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