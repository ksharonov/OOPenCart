<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Client;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'title',
//            'type',
            [
                'attribute' => 'type',
                'filter' => Client::$types,
                'value' => function (Client $model) {
                    return Client::$types[$model->type] ?? 'Неизвестный';
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Client::$statuses,
                'value' => function (Client $model) {
                    return Client::$statuses[$model->status] ?? 'Неизвестный';
                }
            ],
            // 'dtUpdate',
            // 'dtCreate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
