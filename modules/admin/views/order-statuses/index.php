<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\OrderStatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статусы заказа';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
