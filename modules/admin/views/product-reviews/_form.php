<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductReview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-review-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'productId')->textInput() ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= $form->field($model, 'dtCreate')->textInput() ?>

    <?= $form->field($model, 'positive')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'negative')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dtUpdate')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\db\ProductReview::$reviews) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
