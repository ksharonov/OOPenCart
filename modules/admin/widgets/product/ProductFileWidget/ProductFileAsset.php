<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15-12-2017
 * Time: 10:16 AM
 */

namespace app\modules\admin\widgets\product\ProductFileWidget;

use yii\web\AssetBundle;

class ProductFileAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/widgets/product/ProductFileWidget';

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
        'yii\bootstrap\BootstrapAsset'
    ];
}