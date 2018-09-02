<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории продуктов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->isDefault) {
                return ['class' => 'success'];
            };
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
//                'filter' => \yii\helpers\ArrayHelper::map(\app\models\db\ProductCategory::find()->all(), 'id', 'title'),
            ],
            [
                'attribute' => 'parent',
                'label' => 'Родитель',
                'value' => function ($model) {
                    return $model->parent->title ?? null;
                },
//                'filter' => \yii\helpers\ArrayHelper::map(\app\models\db\ProductCategory::find()->all(), 'id', 'title'),
            ],
            //'content:ntext',
            // 'parentId',
            [
                'attribute' => 'isDefault',
                'filter' => ['Нет', 'Да'],
                'value' => function ($model) {
                    return $model->isDefault ? 'Да' : 'Нет';
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
