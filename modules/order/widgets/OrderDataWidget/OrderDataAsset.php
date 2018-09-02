<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 29-01-2018
 * Time: 12:06 PM
 */

namespace app\modules\order\widgets\OrderDataWidget;

use yii\web\AssetBundle;

class OrderDataAsset extends AssetBundle
{
    public $sourcePath = '@order/widgets/OrderDataWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
//        'css/style.css'
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset'
//        'yii\bootstrap\BootstrapAsset',
    ];
}