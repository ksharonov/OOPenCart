<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Post;

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var \app\models\search\PostSearch $searchModel */
$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <p>
        <?= Html::a('Добавить новость', ['pre-create', 'type' => Post::TYPE_NEWS], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить обзор', ['pre-create', 'type' => Post::TYPE_REVIEWS], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить вакансию', ['pre-create', 'type' => Post::TYPE_VACANCY], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'type',
                'filter' => Post::$types,
                'value' => function ($model) {
                    /* @var Post $model */
                    return Post::$types[$model->type];
                }
            ],

            'title',
            'dtCreate',
            [
                'attribute' => 'status',
                'filter' => Post::$postStatuses,
                'value' => function ($model) {
                    /* @var Post $model */
                    return Post::$postStatuses[$model->status];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' =>
                    [

                        //view button
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "$url&type={$model->type}", [
                                'title' => Yii::t('app', 'Update'),
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'data-confirm' => 'Are you sure you want to delete this item?',
                                'data-method' => 'post',

                            ]);
                        },
                    ],
            ],
        ],
    ]); ?>
</div>