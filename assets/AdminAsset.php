<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'css/admin.css',
        'css/admin/product-attributes.css'
    ];
    public $js = [
        'js/admin/script.js',
        'js/admin/manufacturers.js',
        'js/admin/product.js',
        'js/admin/product-attributes.js',
        'js/admin/post-categories.js',
        'js/admin/product-filters.js',
        'js/admin/product-option-values.js',
        'js/admin/translit.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset',
        'kartik\dynagrid\DynaGridAsset'
    ];
}