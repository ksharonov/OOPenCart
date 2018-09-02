<?php

use yii\helpers\Html;
use app\models\db\Order;
use yii\helpers\ArrayHelper;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use kartik\dynagrid\DynaGrid;
use kartik\daterange\DateRangePicker;
use app\models\db\ProductCategory;
use app\models\db\OrderStatus;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?=
    DynaGrid::widget([
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => 'Дата',
                'attribute' => 'dtCreate',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'value' => function (Order $model) {
                    return $model->dtc;
                },
                'filterWidgetOptions' => ([
                    'model' => $searchModel,
                    'attribute' => 'dt',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => ['format' => 'd.m.Y'],
                    ]
                ])
            ],
            [
                'attribute' => 'clientId',
                'value' => function ($model) {
                    return $model->client->title ?? null;
                }
            ],
//            [
//                'attribute' => 'status',
//                'value' => function (Order $model) {
//                    return $model->lastStatus->title;
//                    return Order::$statuses[$model->lastStatus] ?? null;
//                },
//                'filter' => Select2::widget([
//                    'model' => $searchModel,
//                    'attribute' => 'status',
//                    'data' => Order::$statuses,
//                    'theme' => Select2::THEME_DEFAULT,
//                    'options' => [
//                        'placeholder' => 'Выбор статуса',
//                    ]
//                ])
//            ],
            [
                'label' => 'Статус заказа',
                'attribute' => 'orderStatus',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'orderStatus',
                    'data' => ArrayHelper::map(OrderStatus::find()->all(), 'id', 'title'),
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => [
                        'placeholder' => 'Выбор статуса',
                    ]
                ]),
                'value' => function (Order $model) {
                    return $model->lastStatus->title ?? null;
                }
            ],
            [
                'attribute' => 'userId',
                'value' => function ($model) {
                    return $model->user->username ?? null;
                }
            ],
            [
                'attribute' => 'addressId',
                'value' => function ($model) {
                    return $model->address->address ?? null;
                }
            ],
            [
                'label' => 'Способ оплаты',
                'value' => function (Order $model) {
                    return $model->payment->extensionInstance->title ?? null;
                }
            ],
            [
                'label' => 'Способ доставки',
                'value' => function (Order $model) {
                    return $model->delivery->extension->title ?? null;
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],//{delete}
        ],
        'showFilter' => true,
        'storage' => DynaGrid::TYPE_COOKIE,
        'theme' => 'panel-info',
        'gridOptions' => [
            'responsive' => true,
            'hover' => true,
            'pjax' => false,
            'resizableColumns' => true,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<h3 class="panel-title">{dynagridFilter}</h3>',
                'before' => '{dynagrid}'
            ],
        ],
        'options' => ['id' => 'orders']
    ]);
    ?>

</div>
