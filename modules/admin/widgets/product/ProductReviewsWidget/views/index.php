<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\db\ProductAttribute;

/* @var app\models\db\ProductAttribute $model */
/* @var app\models\db\Product $productModel */
/* @var app\models\search\ProductAttributeSearch $attributeSearchModel */
/* @var yii\data\ActiveDataProvider $attributesProvider */
?>

<div class="product-reviews-widget">
    <?php Pjax::begin(['timeout' => 10000, 'id' => 'analogues']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-reviews'],
        'rowOptions' => ['class' => 'product-reviews__item'],
        'columns' => [
            'title',
            'comment',
            'rating',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return '<a href="/admin/product-reviews/update?id=' . $model->id . '" title="Удалить" aria-label="Удалить" data-pjax="0" data-method="post"><span class="glyphicon glyphicon-pencil"></span></a>';
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>