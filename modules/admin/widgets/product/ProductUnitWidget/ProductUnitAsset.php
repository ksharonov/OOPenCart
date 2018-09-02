<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 08-12-2017
 * Time: 14:18 PM
 */

namespace app\modules\admin\widgets\product\ProductUnitWidget;

use yii\web\AssetBundle;

class ProductUnitAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductUnitWidget';

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