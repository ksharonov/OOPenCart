<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $publishOptions = [
        'forceCopy' => false,
    ];

    public $css = [
//        'css/site.css',
    ];

    public $js = [
        //'https://www.google.com/recaptcha/api.js',
        'js/helpers/CookieHelper.js',
        'js/helpers/PjaxHelper.js',
        'js/helpers/UrlHelper.js',
        'js/helpers/HtmlHelper.js',
        'https://api-maps.yandex.ru/2.1/?lang=ru_RU&load=package.full&onload=initShopMaps'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\widgets\PjaxAsset',
    ];
}
