<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы на продукт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-review-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product Review', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'comment:ntext',
            [
                'attribute' => 'productId',
                'value' => function (\app\models\db\ProductReview $model) {
                    return $model->product->title ?? '';
                }
            ],
            [
                'attribute' => 'userId',
                'value' => function (\app\models\db\ProductReview $model) {
                    return $model->user->username ?? $model->author;
                }
            ],
            // 'rating',
            // 'dtCreate',
            // 'positive:ntext',
            // 'negative:ntext',
            // 'dtUpdate',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
