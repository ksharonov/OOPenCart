<?php

namespace app\modules\admin\widgets\common\SeoEditWidget;

use yii\web\AssetBundle;

class SeoEditAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/common/SeoEditWidget';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [

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