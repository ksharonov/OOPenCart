<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;
use app\helpers\ModelRelationHelper;

/* @var $this yii\web\View */
/* @var $model app\models\db\Seo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <?= DynamicParamsWidget::widget([
        'model' => $model,
        'attribute' => 'params'
    ]); ?>

    <?= $form->field($model, 'relModel')->dropDownList(ModelRelationHelper::$relModels) ?>

    <?= $form->field($model, 'relModelId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
