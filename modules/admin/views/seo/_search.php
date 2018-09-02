<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SeoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'meta_keywords') ?>

    <?= $form->field($model, 'meta_description') ?>

    <?= $form->field($model, 'params') ?>

    <?php // echo $form->field($model, 'relModel') ?>

    <?php // echo $form->field($model, 'relModelId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
