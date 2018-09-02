<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ProductReviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-review-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'productId') ?>

    <?= $form->field($model, 'userId') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'dtCreate') ?>

    <?php // echo $form->field($model, 'positive') ?>

    <?php // echo $form->field($model, 'negative') ?>

    <?php // echo $form->field($model, 'dtUpdate') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
