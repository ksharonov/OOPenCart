<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расширения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    DynaGrid::widget([
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'title',
                'visible' => true
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'name',
                'visible' => true,
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'class',
                'visible' => true,
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'type',
                'value' => function ($model) {
                    return \app\models\db\Extension::$types[$model->type];
                }
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model) {
                    return \app\models\db\Extension::$statuses[$model->status];
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
        ],
        'showFilter' => true,
        'storage' => DynaGrid::TYPE_COOKIE,
        'theme' => 'panel-info',
        'gridOptions' => [
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'resizableColumns' => true,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<h3 class="panel-title">{dynagridFilter}</h3>',
                'before' => '{dynagrid}'// . Html::a('Custom Button', '#', ['class' => 'btn btn-default'])
            ],
        ],
        'options' => ['id' => 'extensions'] // a unique identifier is important
    ]);
    ?>
</div>
