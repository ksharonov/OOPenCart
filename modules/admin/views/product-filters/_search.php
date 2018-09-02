<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ProductFilterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-filter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'source') ?>

    <?= $form->field($model, 'sourceId') ?>

    <?= $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'expanded') ?>

    <?php // echo $form->field($model, 'params') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
