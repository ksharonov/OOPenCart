<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\db\ProductCategory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var yii\web\View $this */
/* @var app\models\search\BlockSearch $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="block-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title')
        ->label('Название') ?>

    <?= $form->field($model, 'category')
        ->label('Категория')
        ->widget(Select2::classname(), [
            'data' => ArrayHelper::map(ProductCategory::find()->all(), 'id', 'title'),
            'language' => 'ru',
            'options' => [
                'placeholder' => 'Выбрать категорию товара',
                'id' => 'source',
                'style' => 'width: 100%;'
            ],
            'theme' => Select2::THEME_DEFAULT,
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => false
            ]
        ])
    ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
