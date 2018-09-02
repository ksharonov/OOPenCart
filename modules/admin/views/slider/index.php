<?php
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;

$this->title = 'Слайдер';

$form = ActiveForm::begin();
echo $form->field($model, 'setValue')
    ->label(false)
    ->widget(MultipleInput::className(), [
    'max' => 10,
    'allowEmptyList' => true,
    'columns' => [
        [
            'name' => 'title',
            'title' => 'Название',
            'defaultValue' => null,
        ],
        [
            'name' => 'img',
            'title' => 'Изображение',
            'type' => \mihaildev\elfinder\InputFile::className(),
            'defaultValue' => null,
            'options' => [
                'language' => 'ru',
                'controller' => 'admin/elfinder',
                'path' => 'files',
                'multiple' => false,
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
            ]
        ],
        [
            'name' => 'url',
            'title' => 'Ссылка',
            'enableError' => true,
            'defaultValue' => null,
        ]
    ]
]);

echo Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

ActiveForm::end();
?>
