<?php

use app\system\template\TemplateParams;
use unclead\multipleinput\MultipleInput;
use app\system\template\TemplateSetting;

$modelTest = new TemplateParams();

$modelTest->load(
    [
        'TemplateParams' => $model->$attribute
    ]
);

$tabs = TemplateParams::$attributes;

foreach ($tabs as $tab => $label) {
    echo $form->field($modelTest, $tab)->widget(MultipleInput::className(), TemplateParams::$attributesOptions[$tab])
        ->label($label);
}
