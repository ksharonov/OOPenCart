<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\db\User::$statuses) ?>

    <?= DynamicParamsWidget::widget([
        'model' => $model,
        'attribute' => 'params'
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
