<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ProductAttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Атрибуты продуктов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-index">

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
                'attribute' => 'attributeGroupId',
                'label' => 'Группа атрибутов',
                'value' => function ($model) {
                    if ($model->group) {
                        return $model->group->title;
                    } else {
                        return "Без группы";
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
