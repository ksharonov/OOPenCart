<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\db\Storage;
use kartik\select2\Select2;


/* @var yii\web\View $this */
/* @var app\models\db\Storage $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="storage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'type')
                ->dropDownList(Storage::$types) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')
                ->dropDownList(Storage::$statuses) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'isMain')
                ->dropDownList([0 => 'Нет', 1 => 'Да']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'cityId')
                ->widget(Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\db\City::find()->joinWith('cityOnSite', true, 'INNER JOIN')->all(), 'id', 'title'),
                    'options' => ['placeholder' => 'Выбрать город'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= \app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget::widget(['model' => $model]); ?>

</div>
