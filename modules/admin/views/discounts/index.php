<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Discount;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скидки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'relModel',
                'value' => function (Discount $model) {
                    return \app\helpers\ModelRelationHelper::$relModels[$model->relModel];
                }
            ],
            'relModelId',
            [
                'attribute' => 'type',
                'value' => Discount::$types
            ],
            // 'status',
            // 'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
