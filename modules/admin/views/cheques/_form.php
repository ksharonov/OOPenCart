<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\db\Cheque */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cheque-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'requestId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'success')->textInput() ?>

    <?= $form->field($model, 'params')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dtCreate')->textInput() ?>

    <?= $form->field($model, 'dtUpdate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
