<?php

namespace app\modules\admin\modules\rbac;

use yii\web\AssetBundle;

/**
 * Class RbacAsset
 *
 * @package app\modules\admin\modules\rbac
 */
class RbacAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/yii2mod/yii2-rbac/assets';

    /**
     * @var array
     */
    public $js = [
        'js/rbac.js',
    ];

    public $css = [
        'css/rbac.css',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
