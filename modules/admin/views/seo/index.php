<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Seo;
use app\models\db\Product;
use app\models\db\Post;
use app\models\db\Page;
use app\helpers\ModelRelationHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-index">

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
            'meta_keywords',
            'meta_description',
//            'params',
            [
                'attribute' => 'relModel',
                'value' => function ($model) {
                    return ModelRelationHelper::$relModels[$model->relModel] ?? null;
                }
            ],
            [
                'attribute' => 'relModelId',
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!$model->relModel) {
                        return null;
                    }
                    if ($model->relModel == ModelRelationHelper::REL_MODEL_PRODUCT) {
                        return Html::a($model->product->title, "/admin/products/update?id={$model->product->id}");
                    } elseif ($model->relModel == ModelRelationHelper::REL_MODEL_POST) {
                        return Html::a($model->post->title, "/admin/posts/update?id={$model->product->id}");
                    } elseif ($model->relModel == ModelRelationHelper::REL_MODEL_PAGE) {
                        return Html::a($model->page->title, "/admin/pages/update?id={$model->product->id}");
                    } else {
                        return null;
                    }
                }

            ],
            // 'relModelId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
