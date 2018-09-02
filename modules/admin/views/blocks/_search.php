<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\BlockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'key') ?>

    <?= $form->field($model, 'value') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'dtUpdate') ?>

    <?php // echo $form->field($model, 'dtCreate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
