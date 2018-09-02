<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\models\db\Product;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\db\ProductCategory;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <p>
        <?= Html::a('Добавить', ['pre-create'], ['class' => 'btn btn-flat btn-sm btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'backCode',
            'vendorCode',
            [
                'label' => 'Категория',
                'attribute' => 'category',
                'value' => function (Product $product) {
                    return $product->categoriesString;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'category',
                    'data' => ArrayHelper::map(ProductCategory::find()
                        ->select('id, title')
                        ->where('parentId is NULL')
                        ->all(), 'id', 'title'),
                    'theme' => Select2::THEME_DEFAULT,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options' => [
                        'placeholder' => 'Выбор категории',
                    ]
                ])
            ],
            [
                'attribute' => 'status',
                'filter' => Product::$statuses,
                'value' => function ($model) {
                    return Product::$statuses[$model->status];
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
