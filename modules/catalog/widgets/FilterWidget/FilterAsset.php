<?php

namespace app\modules\catalog\widgets\FilterWidget;

use yii\web\AssetBundle;

class FilterAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/catalog/widgets/FilterWidget';

    public $publishOptions = [
        'forceCopy' => false
    ];

    public $css = [
        'css/style.css'
    ];
    public $js = [
        'js/script.js'
    ];

    public $depends = [
    ];
}