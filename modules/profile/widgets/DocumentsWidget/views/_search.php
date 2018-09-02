<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\db\OrderStatus;
use app\widgets\form\InlineSelectWidget\InlineSelectWidget;

/* @var $this yii\web\View */
/* @var $model app\models\search\BlockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="block-search">

    <?php $form = ActiveForm::begin([
        'action' => ['/profile/index'],
        'method' => 'get',
        'options' => ['data-pjax' => true]
    ]); ?>

    <?= $form->field($model, 'id')
        ->textInput()
        ->label('id файла') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
