<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 19-12-2017
 * Time: 12:08 PM
 */

namespace app\modules\admin\widgets\common\DynamicParamsWidget;

use yii\web\AssetBundle;

class DynamicParamsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/common/DynamicParamsWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/script.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset'
    ];
}