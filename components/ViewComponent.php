<?php

namespace app\components;

use yii\web\View;
use app\system\template\TemplateBase;
use app\system\base\Controller;

/**
 * Class ViewComponent
 *
 * Переопределённый компонент вью-инстанса
 *
 * @package app\components
 */
class ViewComponent extends View
{
    /** @var Controller */
    public $controller = null;

    /** @var TemplateBase */
    public $context = null;

    /**
     * Установка контекста
     * @param $context
     */
    public function setContextData($context)
    {
        $this->context = $context;
        $this->controller = $context;
    }
}