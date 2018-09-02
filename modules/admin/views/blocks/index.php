<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Визуальные и функциональные блоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-index">

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
                'attribute' => 'blockKey',
                'visible' => true,
            ],
            [
                'class' => 'kartik\grid\DataColumn',
                'attribute' => 'type',
                'value' => function ($model) {
                    return \app\models\db\Block::$types[$model->type];
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
        'options' => ['id' => 'blocks'] // a unique identifier is important
    ]);
    ?>
</div>
