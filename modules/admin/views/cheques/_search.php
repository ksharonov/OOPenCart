<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ChequeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cheque-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'requestId') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'success') ?>

    <?= $form->field($model, 'params') ?>

    <?php // echo $form->field($model, 'dtCreate') ?>

    <?php // echo $form->field($model, 'dtUpdate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
