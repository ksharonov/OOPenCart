<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Прайсы цен';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-price-group-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
