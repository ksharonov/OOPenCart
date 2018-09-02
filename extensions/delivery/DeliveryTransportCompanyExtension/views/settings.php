<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var \yii\web\View $this */
/* @var \app\models\db\Order $order */
/* @var array $extensionParams */
?>

<?php $form->field($order, "deliveryData[$id][test1]", ['options' => [
    'class' => 'checkout__col'
],
    'labelOptions' => [
        'class' => 'checkout__label'
    ],
    'inputOptions' => [
        'class' => 'checkout__input'
    ]])
    ->textInput()
    ->label("Test 3") ?>