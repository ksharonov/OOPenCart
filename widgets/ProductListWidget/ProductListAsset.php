<?php

namespace app\widgets\ProductListWidget;

use yii\web\AssetBundle;

class ProductListAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/ProductListWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/script.js'
    ];
}