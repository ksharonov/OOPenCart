<?php

namespace app\modules\admin\widgets\product\ProductAttributeWidget\assets;

use yii\web\AssetBundle;

class FilterFastRelationAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductAttributeWidget';

    public $css = [
    ];

    public $js = [
        'js/filterFastRel.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}