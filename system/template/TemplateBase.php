<?php

namespace app\system\template;

use yii\base\Widget;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class TemplateBase
 *
 * Базовый класс шаблона от которого надо наследоваться
 *
 * @package app\system\template
 */
class TemplateBase extends Widget
{
    const COMMON_LAYOUT = 'layout';
    const COMMON_HEADER = 'header';
    const COMMON_CONTENT = 'content';
    const COMMON_FOOTER = 'footer';


    const MODULE_ERROR = 'error';
    const MODULE_SITE = 'site';
    const MODULE_CART = 'cart';
    const MODULE_CATALOG = 'catalog';
    const MODULE_ORDER = 'order';
    const MODULE_PRODUCT = 'product';
    const MODULE_PROFILE = 'profile';
    const MODULE_WIDGET = 'widget';

    public $fileAsset = "asset\Asset";

    public $fileParams = "Params";

    /**
     * @var array названия вью-файлов
     */
    public static $elements = [
        self::MODULE_SITE => [
            self::COMMON_LAYOUT => 'site/common/layout',
            self::COMMON_HEADER => 'site/common/header',
            self::COMMON_CONTENT => 'site/common/content',
            self::COMMON_FOOTER => 'site/common/footer'
        ],
        self::MODULE_ERROR => [
            self::COMMON_CONTENT => 'error/common/content',
        ],
        self::MODULE_CATALOG => [
            self::COMMON_CONTENT => 'catalog/common/content',
        ],
        self::MODULE_ORDER => [
            self::COMMON_CONTENT => 'order/common/content',
        ],
        self::MODULE_PRODUCT => [
            self::COMMON_CONTENT => 'product/common/content'
        ],
        self::MODULE_PROFILE => [
            self::COMMON_CONTENT => 'profile/common/content'
        ],
        self::MODULE_CART => [
            self::COMMON_CONTENT => 'cart/common/content'
        ],
        self::MODULE_WIDGET => [

        ]
    ];

    /**
     * Основные параметры шаблона
     *
     * @var array
     */
    public $params = [
        [
            "title" => "title",
            "key" => "key",
            "value" => "value"
        ]
    ];

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        $this->assetLoader();
        parent::init();
    }

    /**
     * Получение имени вью-файла
     *
     * @param $moduleId
     * @param $elementName
     * @return string
     */
    public static function getElementName($moduleId, $elementName): string
    {
        return self::$elements[$moduleId][$elementName] ?? self::$elements[self::MODULE_SITE][$elementName];
    }

    /**
     * Загрузчик ассетов шаблона
     *
     * @return void
     */
    public function assetLoader()
    {
        /** @var View $view */
        $view = $this->getView();

        $reflection = new \ReflectionClass($this);
        $namespace = $reflection->getNamespaceName();

        /** @var AssetBundle $classAsset */
        $classAsset = $namespace . "\\" . $this->fileAsset;
        $classAsset::register($view);
    }

    /**
     * Рендер внутри шаблона
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        $templateLoader = TemplateLoader::getInstance();
        $viewName = $templateLoader->options->viewPath . '/' . $view;
        return parent::render($viewName, $params);
    }

}