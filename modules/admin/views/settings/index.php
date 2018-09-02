<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\dynagrid\DynaGrid;
use app\models\db\Setting;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Параметры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    DynaGrid::widget([
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'title',
                'visible' => true,
                'options' => [
                  'style' => 'width: 20%'
                ],
                'contentOptions' => [
                  'style' => 'text-align: left;'
                ],
                'editableOptions' => [
                ],
            ],
//            [
//                'class' => 'kartik\grid\EditableColumn',
//                'attribute' => 'setKey',
//                'visible' => true,
//                'editableOptions' => [
//                ],
//            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'options' => [
                    'style' => 'width: 75%'
                ],
                'editableOptions' => function (Setting $model) {

                    $className = $model->sourceData->classname ?? false;

                    if ($className) {
                        $objectData = $className::find()->all();
                    } else {
                        $objectData = (object)[];
                    }

                    $data = \yii\helpers\ArrayHelper::map($objectData, 'id', 'title') ?? [];

                    if ($model->type != null) {
                        $inputType = Setting::$typesRel[$model->type];
                    } else {
                        $inputType = Editable::INPUT_TEXT;
                    }


                    if (!(array)$objectData && $model->type == Setting::TYPE_SELECT) {
                        $data = $model->params ?? $data;
                    }

                    $displayValue = $model->setValue ?? null;

                    return [
                        'displayValue' => $displayValue,
                        'size' => 'lg',
                        'placement' => 'left',
                        'inputType' => $inputType,
                        'options' => [
                            'data' => $data,
                            'options' => [
                                'placeholder' => "Выбрать...",
                                'multiple' => false,
                            ]
                        ]
                    ];
                },
                'attribute' => 'setValue',
                'visible' => true,
//                'editableOptions' => [
//                ],
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
        'options' => ['id' => 'setting'] // a unique identifier is important
    ]);
    ?>
</div>
