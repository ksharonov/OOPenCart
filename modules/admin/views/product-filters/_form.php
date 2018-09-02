<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\db\ProductFilter;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use app\models\db\Product;
use yii\helpers\ArrayHelper;
use app\models\db\ProductAttribute;
use app\modules\admin\widgets\product\ProductCategoryWidget\ProductCategoryWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilter */
/* @var $form yii\widgets\ActiveForm */

$defaultData = [];


if ($model->source == ProductFilter::SOURCE_FIELD) {
    $defaultData = Product::$filteredFields;
} elseif ($model->source == ProductFilter::SOURCE_ATTRIBUTE) {
    $defaultData = ArrayHelper::map(
        ProductAttribute::find()
            ->where(['id' => $model->sourceId])
            ->all(),
        'id', 'title');
}
?>

<div class="product-filter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'source')->dropDownList(ProductFilter::$sources) ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'sourceId')->widget(Select2::classname(), [
                'data' => $defaultData,
                'language' => 'ru',
                'options' => [
                    'placeholder' => 'Выбрать группу атрибутов',
                    'id' => 'source',
                    'style' => 'width: 100%;'
                ],
                'theme' => Select2::THEME_DEFAULT,
                'pluginOptions' => [
                    'allowClear' => false,
                    'multiple' => false,
                ]
            ]); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'type')->dropDownList(ProductFilter::$types) ?>
        </div>
    </div>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'expanded')->dropDownList(ProductFilter::$expendedStatuses) ?>


    <?= ProductCategoryWidget::widget([
        'model' => $model,
        'relationModel' => 'ProductFilterToCategory',
        'relationId' => 'filterId'
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
