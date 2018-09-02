<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\Extension */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extension-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(\app\models\db\Extension::$types) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\db\Extension::$statuses) ?>


    <?= $form->field($model, 'access')->dropDownList(\app\models\db\Extension::$accesses) ?>


    <?php
    echo DynamicParamsWidget::widget([
        'label' => 'Параметры виджета',
        'model' => $model,
        'attribute' => 'params',
        'extendable' => false
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
