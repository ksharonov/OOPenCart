<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\extensions\payment\TestPaymentExtension\TestPaymentExtension;

/* @var \app\models\db\Order $order */
/* @var array $extensionParams */
/* @var array $fields Поля виджета оплаты */

Pjax::begin(['timeout' => 10000, 'enablePushState' => false]);

$form = ActiveForm::begin([
    'action' => '/order/',
    'options' => ['data-pjax' => true],
    'method' => 'POST'
]);

echo $form->field($order, 'paymentMethod')
    ->hiddenInput(['value' => $id])
    ->label(false);

//Обновление Pjax с использованием циклов не работает
foreach ($fields as $key => $value) {
    echo $form->field($order, "paymentData[$key]")
        ->textInput(['value' => $value])
        ->label($key);
}

echo Html::submitButton('Задать', [
    'class' => 'btn btn-primary'
]);

ActiveForm::end();

Pjax::end();