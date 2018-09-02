<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\db\ProductAttribute;

/* @var app\models\db\ProductAttribute $model */
/* @var app\models\db\Product $productModel */
/* @var app\models\search\ProductAttributeSearch $attributeSearchModel */
/* @var yii\data\ActiveDataProvider $attributesProvider */
?>

<div class="product-analogue-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAnalogueModal">
        Добавить аналог товара
    </button>
    <br><br>


    <div class="modal fade" id="addAnalogueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Атрибуты</h4>
                </div>
                <div class="modal-body">
                    <?php Pjax::begin(['timeout' => 10000]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $productProvider,
                        'filterModel' => $productSearchModel,
                        'summary' => false,
                        'columns' => [
                            'title',
                            'backCode',
                            'vendorCode',
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($productModel) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'attribute-add btn btn-primary btn-sm',
                                        'onclick' => "setProductAnalogue($productModel->id, $model->id)"
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


    <?php Pjax::begin(['timeout' => 10000, 'id' => 'analogues']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-analogues'],
        'rowOptions' => ['class' => 'product-analogues__item'],
        'columns' => [
            [
                'attribute' => 'product.title',
                'label' => 'Название аналога',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->product->title, "/admin/products/update?id={$model->product->id}");
                }
            ],
            [
                'attribute' => 'productAnalogue.title',
                'label' => 'Название аналога',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->productAnalogue->title, "/admin/products/update?id={$model->productAnalogue->id}");
                }
            ],
            [
                'attribute' => 'backcomp',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::checkbox('backcomp', $model->backcomp, ['class' => 'product-analogues__backcomp', 'onclick' => "toggleAnalogue({$model->id})"]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>


</div>