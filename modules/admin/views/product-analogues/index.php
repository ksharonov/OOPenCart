<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductAnalogueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аналог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-analogue-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'productId',
            'productAnalogueId',
            'backcomp',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
