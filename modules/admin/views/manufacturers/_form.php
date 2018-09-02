<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model app\models\db\Manufacturer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manufacturer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
    /*
    $form->field($model, 'image')->widget(InputFile::className(), [
        'controller' => 'admin/elfinder',
        'path' => 'image',
        'filter' => 'image',
        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control _manufacturer_image_path'],
        'buttonOptions' => ['class' => 'btn btn-default'],
        'multiple' => false
    ])
    */
    ?>

    <?php if ($model->file) { ?>
        <div class="row">
            <div class="col-md-2">
                <a href="#" class="thumbnail manufacturer-form__image-link">
                    <img class="manufacturer-form__image" src="<?= $model->file->path ?>">
                </a>
            </div>
        </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= \app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget::widget(['model' => $model]); ?>

</div>
