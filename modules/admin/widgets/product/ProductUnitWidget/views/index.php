<?php

use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\bootstrap\Html;

/* @var app\models\db\ProductUnit $model */
/* @var app\models\db\Product $productModel */
/* @var app\models\search\UnitSearch $unitSearchModel */
/* @var yii\data\ActiveDataProvider $unitProvider */
?>

<div class="product-unit-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal">
        Добавить единицу измерения
    </button>
    <br><br>
    <div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Группы цен</h4>
                </div>
                <div class="modal-body">
                    <?php Pjax::begin(['timeout' => 10000]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $unitProvider,
                        'filterModel' => $unitSearchModel,
                        'summary' => false,
                        'columns' => [
                            'title',
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($productModel) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'unit-add btn btn-primary btn-sm',
                                        'onclick' => "adminProductUnit.set($productModel->id, $model->id)"
                                    ]);
                                }
                            ]
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(['timeout' => 10000, 'id' => 'units']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-unit'],
        'rowOptions' => ['class' => 'product-unit__item'],
        'columns' => [
            'unit.title',
            [
                'attribute' => 'rate',
                'format' => 'raw',
                'value' => function ($model) use ($productModel) {
                    return Html::input('string', 'value', $model->rate, [
                        'class' => 'form-control product-unit__value-item',
                        'data-product-id' => $productModel->id,
                        'data-unit-id' => $model->unit->id,
                    ]);
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'contentOptions' => ['class' => 'product-unit__actions'],
                'value' => function ($model) use ($productModel) {
                    return
                        Html::tag('a', 'Удалить', [
                            'href' => '#',
                            'class' => 'product-unit__delete-button btn btn-primary btn-sm',
                            'onclick' => "adminProductUnit.delete($model->id)"
                        ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
