<?php

namespace app\widgets\form\InlineSelectWidget;

use yii\base\Widget;
//use yii\widgets\InputWidget;
use app\system\base\widgets\InputWidget;
use yii\helpers\Html;
use app\widgets\form\InlineSelectWidget\InlineSelectAsset;

/**
 * Class InlineSelectWidget
 * Виджет для инлайнового представления селектора.
 * В данном случае представлен в виде кнопок.
 *
 * @package app\widgets\form\InlineSelectWidget
 */
class InlineSelectWidget extends InputWidget
{
    /**
     * @var array массив входных данных
     */
    public $data = [];

    public function run()
    {
        $view = $this->getView();
        InlineSelectAsset::register($view);

        $name = (new \ReflectionClass($this->model))->getShortName();

        if (!is_array($this->model->{$this->attribute})) {
            $this->model->{$this->attribute} = [];
        }

        return $this->render('index', [
            'formName' => $this->field->form->id,
            'name' => $name,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'data' => $this->data
        ]);
    }
}