<?php

namespace app\modules\admin\widgets\client\ClientUsersWidget;

use yii\web\AssetBundle;

class ClientUsersAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/client/ClientUsersWidget';

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