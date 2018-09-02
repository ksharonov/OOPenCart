<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\db\Product */
/* @var $seoModel \app\models\db\Seo
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'seo-form']]); ?>

<?= $form->field($seoModel, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($seoModel, 'meta_keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($seoModel, 'meta_description')->textInput(['maxlength' => true]) ?>

<?= DynamicParamsWidget::widget([
    'model' => $seoModel,
    'attribute' => 'params'
]); ?>

<?= $form->field($seoModel, 'relModel')
    ->hiddenInput(['value' => \app\helpers\ModelRelationHelper::REL_MODEL_PRODUCT])
    ->label(false) ?>

<?= $form->field($seoModel, 'relModelId')
    ->hiddenInput(['value' => $model->id])
    ->label(false) ?>

<?php ActiveForm::end(); ?>
