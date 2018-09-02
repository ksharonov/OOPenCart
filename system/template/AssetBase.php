<?php

namespace app\system\template;

use yii\web\AssetBundle;

/**
 * Class AssetLoader
 *
 * Будущий загрузчик ассетов
 *
 * @package app\system\template
 */
class AssetBase extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];

}