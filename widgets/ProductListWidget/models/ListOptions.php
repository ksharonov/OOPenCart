<?php

namespace app\widgets\ProductListWidget\models;

use yii\base\BaseObject;

/**
 * Class ListOptions
 *
 * Опции виджета списка
 *
 * @package app\widgets\ProductListWidget\models
 */
class ListOptions extends BaseObject
{
    public $viewElements = [
        'header' => null,
        'footer' => null
    ];

    /**
     * Получаем дополнительный view-элемент списка
     * @param $element
     */
    public function getViewElement($element)
    {

    }
}