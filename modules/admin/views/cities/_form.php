<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\db\CityOnSite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cities-on-site-form">

    <?php
        $form = ActiveForm::begin();
        $cities = \app\models\db\City::find()
            ->where(['countryId' => 1])
            ->all();

        $items = \yii\helpers\ArrayHelper::map($cities, 'id', 'title');
    ?>

<!--    --><?//= $form->field($model, 'cityId')->textInput()->label('Город') ?>

    <?= $form->field($model, 'cityId')->dropDownList($items)?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
