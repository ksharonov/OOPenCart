<?php

namespace app\modules\catalog\widgets\FastFilterWidget;

use yii\web\AssetBundle;

class FastFilterAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/catalog/widgets/FastFilterWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'app\modules\catalog\widgets\FilterWidget\FilterAsset'
        //'yii\bootstrap\BootstrapAsset',
    ];
}