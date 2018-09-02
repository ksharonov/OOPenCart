<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ApikeySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apikeys';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apikey-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Apikey', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'userId',
            'clientId',
            'keyValue',
            'dtCreate',
            // 'duration',
            // 'status',
            // 'permission',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
