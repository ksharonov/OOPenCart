<?php

namespace app\system\base;

use yii\web\View;

/**
 * Class Renderer
 *
 * Рендерер вьюх
 *
 * @package app\system\base
 */
class Renderer
{
    public static function getView($viewName, $params, $context)
    {
        $view = new View();
        $view->render($viewName, $params, $context);
    }
}