<?php

namespace app\modules\catalog\widgets\SortWidget;

use yii\web\AssetBundle;

class SortAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/catalog/widgets/SortWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}