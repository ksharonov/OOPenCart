<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 03.04.2018
 * Time: 15:03
 */

namespace app\modules\profile\widgets\ReconciliationWidget;


use yii\web\AssetBundle;

class ReconciliationAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/profile/widgets/ReconciliationWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'css/style.css'
    ];
    public $js = [
        'js/checkAct.js',
        'js/reloadDatePicker.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\AppAsset',
    ];
}