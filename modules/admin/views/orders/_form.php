<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\db\Client;
use app\models\db\User;
use app\models\db\Order;
use yii\grid\GridView;
use yii\helpers\Json;
use app\models\db\OrderContent;

/* @var $this yii\web\View */
/* @var $model app\models\db\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="" data-example-id="togglable-tabs">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#main" id="main-tab" role="tab" data-toggle="tab"
                   aria-controls="main" aria-expanded="true">Заказ</a>
            </li>
            <li role="presentation">
                <a href="#address" id="address-tab" role="tab" data-toggle="tab"
                   aria-controls="address" aria-expanded="false">Доставка/Оплата</a>
            </li>
            <li role="presentation">
                <a href="#cheque" id="cheque-tab" role="tab" data-toggle="tab"
                   aria-controls="cheque" aria-expanded="false">Информация по чекам</a>
            </li>
            <li role="presentation">
                <a href="#product" id="product-tab" role="tab" data-toggle="tab"
                   aria-controls="product" aria-expanded="false">Товары</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active in" role="tabpanel" id="main" aria-labelledby="main-tab">
            <br>


            <?php
            echo $form->field($model, 'source')
                ->textInput(['disabled' => true, 'value' => Order::$sources[$model->source] ?? null]);
            ?>
            <div class="form-group">
                <?php
                echo Html::label('Заказчик');
                echo Html::textInput('user',
                    $model->user->fio ?? $model->user->username ?? $model->user->email ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo Html::label('Телефон заказчика');
                echo Html::textInput('user-phone',
                    $model->user->phone ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo Html::label('Клиент');
                echo Html::textInput('client',
                    $model->client->title ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo Html::label('Телефон клиента');
                echo Html::textInput('client-phone',
                    $model->client->phone ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

        <div class="tab-pane fade" role="tabpanel" id="address" aria-labelledby="address-tab">
            <br>
            <div class="form-group">
                <?php
                echo Html::label('Способ оплаты');
                echo Html::textInput('',
                    $model->payment->extensionInstance->title ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo Html::label('Способ доставки');
                echo Html::textInput('',
                    $model->delivery->extension->title ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                $deliveryData = (object)$model->deliveryData;
                if (isset($deliveryData->storageId)) {
                    $storage = \app\models\db\Storage::findOne($deliveryData->storageId);
                    echo Html::label('Склад доставки');
                    echo Html::textInput('',
                        $storage->title ?? null,
                        ['class' => 'form-control', 'disabled' => true]);

                    echo Html::label('Адрес склада');
                    echo Html::textInput('',
                        $storage->address->address ?? null,
                        ['class' => 'form-control', 'disabled' => true]);
                } else {
                    echo Html::label('Данные по доставке');
                    echo Html::textInput('',
                        Json::encode($model->deliveryData) ?? null,
                        ['class' => 'form-control', 'disabled' => true]);
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" role="tabpanel" id="cheque" aria-labelledby="cheque-tab">
            <br>
            <div class="form-group">
                <?php
                echo Html::label('ФД');
                echo Html::textInput('fd',
                    $model->cheque->fd ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo Html::label('ФН');
                echo Html::textInput('fn',
                    $model->cheque->fn ?? null,
                    ['class' => 'form-control', 'disabled' => true]);
                ?>
            </div>
        </div>
        <div class="tab-pane fade" role="tabpanel" id="product" aria-labelledby="product-tab">
            <br>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Название',
                        'value' => function (OrderContent $model) {
                            $title = Json::decode($model->productData)['title'];
                            return $title;
                        }
                    ],
                    'priceValue',
                    'count',
                    [
                        'label' => 'Сумма',
                        'value' => function ($model) {
                            return $model->priceValue * $model->count;
                        }
                    ]
                ],
            ]); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

</div>
