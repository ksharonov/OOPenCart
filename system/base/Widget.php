<?php

namespace app\system\base;

use app\components\ViewComponent;
use app\system\template\TemplateLoader;

/**
 * Class Widget
 *
 * Переопределённый класс виджета для возможности ссылаться на вьюхи в шаблоне
 *
 * @package app\system\base
 */
class Widget extends \yii\base\Widget
{
    /**
     * Включение использования шаблона для виджета
     * Это подразумевает хранение вью-файлов виджета в шаблоне
     * Пока не используется и не оттестировано
     * Это не приоритет сейчас, смотреть позже
     * @var bool
     */
    private static $useTemplate = false;

    /**
     * @param string $viewName
     * @param array $params
     * @return string
     */
    public function render($viewName, $params = []): string
    {
        if (self::$useTemplate) {
            $templateLoader = TemplateLoader::register();
            $templateLoader->setContext($this);
            $templateLoader->setWidgetView($viewName);

            return $templateLoader->renderView($params);
        } else {
            return parent::render($viewName, $params);
        }
    }

    /**
     * @return ViewComponent
     */
    public function getView()
    {
        return TemplateLoader::getView();
    }
}