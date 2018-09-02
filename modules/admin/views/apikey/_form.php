<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\db\Apikey */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apikey-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'clientId')->textInput() ?>

    <?= $form->field($model, 'keyValue')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dtCreate')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'permission')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
