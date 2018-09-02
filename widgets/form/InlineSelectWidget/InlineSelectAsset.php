<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 17-01-2018
 * Time: 14:37 PM
 */

namespace app\widgets\form\InlineSelectWidget;

use yii\web\AssetBundle;

class InlineSelectAsset extends AssetBundle
{

    public $sourcePath = '@app/widgets/form/InlineSelectWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [

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