<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Product;
use app\models\db\ProductFilter;
use app\models\db\ProductAttribute;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductFilterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фильтры товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-index">

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
            [
                'attribute' => 'source',
                'value' => function ($model) {
                    return ProductFilter::$sources[$model->source];
                }
            ],
            [
                'attribute' => 'sourceId',
                'label' => 'Название атрибута',
                'value' => function ($model) {
                    if ($model->source == ProductFilter::SOURCE_FIELD) {
                        return Product::$filteredFields[$model->sourceId];
                    } elseif ($model->source == ProductFilter::SOURCE_ATTRIBUTE) {
                        return ProductAttribute::findOne(['id' => $model->sourceId])->title;
                    }
                }
            ],
            'position',
            // 'expanded',
            // 'params:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
