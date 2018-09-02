<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\db\OrderStatus;
use yii\helpers\ArrayHelper;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model app\models\db\Setting */
/* @var $form yii\widgets\ActiveForm */

echo \app\models\db\Setting::getParam('ORDER.STATUS.NEW', 'classname');
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'setKey')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?php if ($model->setKey == 'DEFAULT.ORDER.ID') { ?>
        <?= $form->field($model, 'setValue')->widget(Select2::classname(), [
            'name' => 'clients',
            'data' => ArrayHelper::map(OrderStatus::find()->all(), 'id', 'title'),
            'language' => 'ru',
            'options' => [
                'placeholder' => 'Выбрать статус',
                'id' => 'statuses',
                'style' => 'width: 100%;'
            ],
            'theme' => Select2::THEME_DEFAULT,
            'pluginOptions' => [
                'allowClear' => false,
                'multiple' => false,
            ]
        ]); ?>
    <?php } else { ?>
        <?= $form->field($model, 'setValue')->textInput(['maxlength' => true]) ?>
    <?php } ?>

    <?= $form->field($model, 'params')->widget(MultipleInput::className(), [
        'max' => 1,
        'min' => 1,
        'allowEmptyList' => true,
        'columns' => [
            [
                'name' => 'title',
                'title' => 'Название поля',
                'defaultValue' => null,
                'options' => [
                    'disabled' => true
                ]
            ],
            [
                'name' => 'key',
                'title' => 'Ключ поля',
                'defaultValue' => null,
                'options' => [
                    'disabled' => true
                ]
            ],
            [
                'name' => 'value',
                'title' => 'Настройка',
                'enableError' => true,
                'defaultValue' => null,
            ]
        ]
    ])
        ->label('Параметры настройки');
    ?>
    <!---->
    <!--    --><?php
    //    if ($model->type !== \app\models\db\Setting::TYPE_TEXT) {
    //        echo DynamicParamsWidget::widget([
    //            'label' => 'Источник',
    //            'model' => $model,
    //            'attribute' => 'source',
    //            'extendable' => false,
    //            'defaultData' => [
    //                'classname' => null
    //            ]
    //        ]);
    //    }
    //    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
