<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CityOnSiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Города на сайте';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-on-site-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'cityId',
                'value' => 'city.title',
                'label' => 'Город',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
