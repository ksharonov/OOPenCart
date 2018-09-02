<?php

namespace app\modules\admin\widgets\common\TemplateParamsWidget;

use Yii;
use yii\base\Widget;

class TemplateParamsWidget extends Widget
{
    public $model;

    public $attribute;

    public $form;

    public function run()
    {
        return $this->render('index', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'form' => $this->form
        ]);
    }

}