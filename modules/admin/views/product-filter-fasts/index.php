<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\db\ProductCategory;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductFilterFastSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Быстрые фильтры товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-fast-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['pre-create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            'name',
            [
                'attribute' => 'categoryId',
                'filter' => ArrayHelper::map(ProductCategory::find()
                    ->select('id, title')
                    ->asArray()
                    ->all(),
                    'id', 'title')
            ],
            'category.title',
//            'expanded',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
