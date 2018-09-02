<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\DiscountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discount-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'relModel') ?>

    <?= $form->field($model, 'relModelId') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'value') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
