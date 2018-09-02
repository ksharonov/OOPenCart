<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\db\Discount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discount-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relModel')->textInput() ?>

    <?= $form->field($model, 'relModelId')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(\app\models\db\Discount::$types) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\db\Discount::$statuses) ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
