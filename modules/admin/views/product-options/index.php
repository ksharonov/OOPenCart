<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\ProductOption;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опции товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-option-index">
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'attribute' => 'type',
                'value' => function($model) {
                    /* @var ProductOption $model */
                    return ProductOption::$productOptionTypes[$model->type];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
