<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use app\modules\admin\widgets\common\TemplateParamsWidget\TemplateParamsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <!--    --><? //= $form->field($model, 'params')->textarea(['rows' => 6]) ?>
    <?php $form->field($model, 'params')->widget(MultipleInput::className(), [
        'max' => 1,
        'min' => 1,
        'allowEmptyList' => true,
        'columns' => [
            [
                'name' => 'title',
                'title' => 'Название поля',
                'defaultValue' => null,
                'options' => [
//                    'disabled' => true
                ]
            ],
            [
                'name' => 'key',
                'title' => 'Ключ поля',
                'defaultValue' => null,
                'options' => [
//                    'disabled' => true
                ]
            ],
            [
                'name' => 'value',
                'title' => 'Значение поля',
                'enableError' => true,
                'defaultValue' => null,
            ]
        ]
    ])
        ->label('Параметры шаблона');
    ?>

    <hr>

    <?= TemplateParamsWidget::widget([
        'model' => $model,
        'attribute' => 'params',
        'form' => $form
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
