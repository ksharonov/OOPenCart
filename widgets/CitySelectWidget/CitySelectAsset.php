<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23.03.2018
 * Time: 15:25
 */

namespace app\widgets\CitySelectWidget;


use yii\web\AssetBundle;

class CitySelectAsset extends AssetBundle
{
    public $sourcePath = "@app/widgets/CitySelectWidget";

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
    ];

}