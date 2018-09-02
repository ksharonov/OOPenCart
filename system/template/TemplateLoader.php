<?php

namespace app\system\template;

use app\system\base\App;
use app\components\ViewComponent;
use Yii;
use app\models\db\Setting;
use app\models\db\Template;
use app\system\template\TemplateBase;
use yii\base\Application;
use yii\base\InlineAction;
use yii\base\Module;
use yii\base\View;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\base\ViewContextInterface;
use yii\base\Exception;


/**
 * Загрузчик шаблонной системы
 *
 * @package app\system
 */
class TemplateLoader
{
    /** @var TemplateLoader */
    private static $instance;

    /**
     * @var ViewComponent вьюха шаблона
     */
    public static $viewInstance = null;

    /** @var TemplateBase */
    private static $templateInstance = null;

    /**
     * @var null
     */
    public static $controller = null;

    /** @var object */
    public $options = null;


    /**
     * TemplateLoader constructor.
     */
    public function __construct()
    {
        /*
         * Задание настроек по умолчанию
         */
        $this->options = (object)[
            'moduleId' => TemplateBase::MODULE_SITE,
            'viewPath' => null
        ];
    }

    /**
     * Регистрация загрузчика шаблона
     *
     * @return TemplateLoader
     */
    public static function register(): TemplateLoader
    {
        $instance = self::getInstance();
        $instance->init();
        return $instance;
    }

    /**
     * Возвращает экземпляр этого класса
     *
     * @return TemplateLoader
     */
    public static function getInstance(): TemplateLoader
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Возвращает текущий экземпляр вьюхи
     * @return ViewComponent
     */
    public static function getView()
    {
        $instance = self::getInstance();
        return $instance::$viewInstance;
    }

    /**
     * Инициализация рендера шаблона
     * Получаем инстанс класса View и класса выбранного шаблона
     * Сохраняем предварительные настройки шаблона в базу
     * @throws Exception
     * @return void
     */
    public function init()
    {
        $instance = self::getInstance();
        $cache = Yii::$app->cache;

        /**
         * Инициализатор приложения
         */
        App::run();

        if (!isset($instance::$templateInstance)) {
            $className = $cache->getOrSet("selected_template", function () {
                $selectedTemplate = Setting::get('TEMPLATE.SELECTED');

                /** @var Template $templateModel */
                $templateModel = Template::findOne($selectedTemplate);

                if (!$templateModel) {
                    throw new Exception("В базе отсутствует шаблон");
                }

                return $templateModel->class;
            });

            $instance::$templateInstance = new $className();
        }

        if (!isset($instance::$viewInstance)) {
            $instance::$viewInstance = Yii::$app->getView();
        }

    }

    /**
     * Рендерит вьюху в контексте виджета
     *
     * @param string $viewName
     * @param array $params
     * @return string
     */
    public function render(string $viewName, array $params = []): string
    {
        $instance = self::getInstance();

        $context = $instance::$templateInstance;
        $view = $instance::$viewInstance;

        $preContent = $view->render($viewName, $params, $context);
        $content = TemplateStore::process($preContent);

        return $content;
    }


    /**
     * Рендерит вьюху, если предварительно загружена вьюха($this) с контекстом
     * через метод setView()
     * @param array $params
     * @return string
     */
    public function renderView(array $params = []): string
    {
        $viewPath = $this->options->viewPath;
        return $this->render($viewPath, $params);
    }

    /**
     * Установка опций для загрузчика
     *
     * @param $options
     */
    public function setOptions(array $options)
    {
        $arrOptions = ArrayHelper::toArray($this->options);
        $this->options = (object)array_merge($arrOptions, $options);
    }

    /**
     * Получаем путь до вьюх
     *
     * @param string $viewName
     */
    public function setControllerView($viewName = null)
    {
        $instance = self::getInstance();

        /** @var Controller $controller */
        $controller = $instance::$viewInstance->context;

        /** @var InlineAction $action */
        $action = $controller->action;

        /** @var Application | Module $module */
        $module = $controller->module;

//        self::$_view = $view;

        $viewRoute = [
            $module->id,
            $controller->id,
            $viewName ?? $action->id
        ];

        $this->options->viewPath = implode('/', $viewRoute);
    }


    /**
     *
     * Путь до вьюхи виджета
     *
     * @param string $viewName
     * @param View $view
     */
    public function setWidgetView($viewName = null, View $view)
    {
        /** @var Widget | object $widget */
        $widget = $view->context;

        $viewRoute = [
            'widget'
        ];

        if (!isset($widget->moduleId)) {
            $viewRoute[] = $widget->moduleId;
        }

        $viewRoute[] = $widget->id;
        $viewRoute[] = $viewName;

        $this->options->viewPath = implode('/', $viewRoute);
    }

    /**
     * Устанавливаем контекст для рендера вьюх
     * @return View
     */
    public function setContext($context = null)
    {
        $instance = self::getInstance();
        $instance::$viewInstance->contextData = $context;
        $instance::$controller = $context;
        return $instance::$viewInstance;
    }

    /**
     * @param string $elementName
     * @param array $params
     * @return string
     */
    public function getElement(string $elementName, array $params = []): string
    {
        /** @var TemplateLoader $instance */
        $instance = self::getInstance();

        /** @var string $moduleId выбранный модуль для рендера */
        $moduleId = $instance->options->moduleId;

        $viewName = TemplateBase::getElementName($moduleId, $elementName);

        return $instance->render($viewName, $params);
    }

    /**
     * Получение освного макета
     * @param array $params
     * @return string
     */
    public function getLayout(array $params = []): string
    {
        $instance = self::getInstance();

        return $instance->getElement(TemplateBase::COMMON_LAYOUT, $params);
    }

    /**
     * Получение шапки сайта
     * @param array $params
     * @return string
     */
    public function getHeader(array $params = []): string
    {
        $instance = self::getInstance();
        return $instance->getElement(TemplateBase::COMMON_HEADER, $params);
    }

    /**
     * Получение контента
     * @param array $params
     * @param array $options
     * @return string
     */
    public function getContent(array $params = [], array $options = []): string
    {
        $instance = self::getInstance();
        return $instance->getElement(TemplateBase::COMMON_CONTENT, $params);
    }

    /**
     * Получение футера
     * @param array $params
     * @return string
     */
    public function getFooter(array $params = []): string
    {
        $instance = self::getInstance();
        return $instance->getElement(TemplateBase::COMMON_FOOTER, $params);
    }

}