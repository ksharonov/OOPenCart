<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ApikeySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apikey-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'clientId') ?>

    <?= $form->field($model, 'keyValue') ?>

    <?= $form->field($model, 'dtCreate') ?>

    <?php // echo $form->field($model, 'duration') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'permission') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
