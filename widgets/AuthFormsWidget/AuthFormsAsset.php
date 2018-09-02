<?php

namespace app\widgets\AuthFormsWidget;

use yii\web\AssetBundle;

class AuthFormsAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/AuthFormsWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $js = [
        'js/script.js'
    ];
}