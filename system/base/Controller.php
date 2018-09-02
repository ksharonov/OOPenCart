<?php

namespace app\system\base;

use Yii;
use app\system\template\TemplateLoader;

/**
 * Class Controller
 *
 * Переопределённый класс контроллера
 *
 * @package app\system\base
 */
class Controller extends \yii\web\Controller
{
    /**
     * Переопределение метода рендер
     * Рендер вьюхи из шаблона
     *
     * @param string $viewName
     * @param array $params
     * @return string
     */
    public function render($viewName, $params = []): string
    {
        $content = $this->preRender($viewName, $params);
        return $this->renderContent($content);
    }

    /**
     * Рендер только нужного элемента без layout
     *
     * @param string $viewName
     * @param array $params
     * @return string
     */
    public function renderPartial($viewName, $params = [])
    {
        return $this->preRender($viewName, $params);
    }

    /**
     * Пререндер для рендера
     *
     * @param $viewName
     * @param array $params
     * @return string
     */
    private function preRender($viewName, $params = []): string
    {
        $templateLoader = TemplateLoader::register();
        $templateLoader->setContext($this);
        $templateLoader->setControllerView($viewName);

        $content = $templateLoader->renderView($params);

        return $content;
    }

}