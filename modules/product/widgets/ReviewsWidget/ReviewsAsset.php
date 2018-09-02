<?php

namespace app\modules\product\widgets\ReviewsWidget;

use yii\web\AssetBundle;

class ReviewsAsset extends AssetBundle
{
    public $sourcePath = '@product/widgets/ReviewsWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [

    ];
    public $js = [
        //'js/script.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];
}