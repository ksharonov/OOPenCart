<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 18-12-2017
 * Time: 9:54 AM
 */

namespace app\modules\admin\widgets\product\ProductAssociatedWidget;

use yii\web\AssetBundle;

class ProductAssociatedAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductAssociatedWidget';

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