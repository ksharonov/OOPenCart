<?php

namespace app\widgets\MainSliderWidget;

use yii\web\AssetBundle;

class MainSliderAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/MainSliderWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];
}