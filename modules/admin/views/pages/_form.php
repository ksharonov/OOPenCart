<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\db\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'data-from-translit' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true, 'data-to-translit' => true]) ?>
        </div>
    </div>


    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
        'options' => ['rows' => 20],
        'language' => 'ru',
        'clientOptions' => [
            'invalid_styles' => [
                'table' => 'height width',
                'td' => 'height width',
                'tr' => 'height width',
                'th' => 'height width',
                'div' => 'class',
                'span' => 'class',
                'p' => 'class'
            ],
            'valid_classes' => [
                '*' => 'class',
            ],
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor image code",
                "searchreplace visualblocks fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image blockquote"
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?= \app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget::widget([
        'model' => $model,
        'withImage' => true
    ]); ?>
</div>
