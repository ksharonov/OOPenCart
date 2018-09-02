<?php
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;


/** @var $cities array */
$this->title = 'Цена доставки для городов';

//{"414":{"minPriceForFree":5000,"deliveryCost":500},"403":{"minPriceForFree":5000,"deliveryCost":500}}

$form = ActiveForm::begin();
echo $form->field($model, 'setValue')
    ->label(false)
    ->widget(MultipleInput::className(), [
        'max' => 10,
        'allowEmptyList' => true,
        'columns' => [
            [
                'name' => 'cityId',
                'title' => 'Город',
                'type' => 'dropDownList',
                'defaultValue' => null,
                'items' => $cities
            ],
            [
                'name' => 'minPriceForFree',
                'title' => 'Минимальная цена для бесплатной доставки',
                'defaultValue' => null
            ],
            [
                'name' => 'deliveryCost',
                'title' => 'Цена доставки',
                'enableError' => true,
                'defaultValue' => null,
            ]
        ]
    ]);

echo Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

ActiveForm::end();
?>
