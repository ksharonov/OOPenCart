<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\Cheque;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ChequeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Чеки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cheque-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'requestId',
            'url:url',
            'path',
            [
                'attribute' => 'success',
                'value' => function (Cheque $model) {
                    return Cheque::$successStatuses[$model->success] ?? null;
                }
            ],
            [
                'attribute' => 'params',
                'format' => 'ntext',
                'value' => function (Cheque $model) {
                    return $model->params;
                }
            ],
            // 'dtCreate',
            // 'dtUpdate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
