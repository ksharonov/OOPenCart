<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ManufacturerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Производители';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-index">

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
