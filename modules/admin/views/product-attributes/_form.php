<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\db\ProductAttributeGroup;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\db\ProductAttribute;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAttribute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attributeGroupId')
        ->label('Группа атрибутов')
        ->widget(Select2::classname(), [
            'data' => array_merge([0 => 'Без группы'], ArrayHelper::map(ProductAttributeGroup::find()->where([])->all(), 'id', 'title')),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выбрать группу атрибутов'],
            'theme' => Select2::THEME_DEFAULT,
            'pluginOptions' => [
                'allowClear' => false,
                'multiple' => false,
            ]
        ]); ?>

    <?= $form->field($model, 'type')->widget(Select2::classname(), [
        'data' => ProductAttribute::$attributeTypes,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выбрать группу атрибутов'],
        'theme' => Select2::THEME_DEFAULT,
        'pluginEvents' => [
            "change" => "function() {
                console.log($(this).val()); 
             }"
        ],
        'pluginOptions' => [
            'allowClear' => false,
            'multiple' => false,
        ]
    ]); ?>

    <?= $form->field($model, 'params')->hiddenInput()->label(false); ?>


    <div class="form-group field-productattribute-inputs required">
        <label class="control-label" for="">Поля</label>
        <div class="attribute-inputs">

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('Добавить выбор', ['class' => 'btn btn-success attribute-input-add', 'style' => 'display:none;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
