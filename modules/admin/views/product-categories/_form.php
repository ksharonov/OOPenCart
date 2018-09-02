<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\db\Product;
use app\models\db\ProductCategory;
use mihaildev\elfinder\InputFile;
use app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'parentId')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(ProductCategory::find()->all(), 'id', 'title'),
                'language' => 'ru',
                'options' => [
                    'placeholder' => 'Выбрать группу атрибутов',
                    'id' => 'parent-category',
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
        <div class="col-md-5">
            <?= $form->field($model, 'thumbnail')->widget(InputFile::className(), [
                'controller' => 'admin/elfinder',
                'path' => 'image',
                'filter' => 'image',
                'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple' => false
            ]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'status')->dropDownList($model::$statuses) ?>
        </div>
    </div>


    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::a('Список товаров', ['/admin/products?ProductSearch[category]=' . $model->id], ['class' => 'btn btn-success']); ?>
        <?= Html::a('Дочерние категории', ['/admin/product-categories?ProductCategorySearch[parent]=' . $model->title], ['class' => 'btn btn-success']); ?>
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord) {
        echo ProductFileWidget::widget(['model' => $model]);
    } ?>

</div>
