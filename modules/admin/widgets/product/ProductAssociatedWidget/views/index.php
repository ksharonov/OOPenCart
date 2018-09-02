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

<div class="product-assoc-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAssocModal">
        Добавить сопутствующий товар
    </button>
    <br><br>


    <div class="modal fade" id="addAssocModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Продукты</h4>
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
                                        'onclick' => "adminProductAssoc.set($productModel->id, $model->id)"
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


    <?php Pjax::begin(['timeout' => 10000, 'id' => 'assocs']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-assoc'],
        'rowOptions' => ['class' => 'product-assoc'],
        'columns' => [
            [
                'attribute' => 'assocProduct.title',
                'label' => 'Название товара',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->assocProduct->title, "/admin/products/update?id={$model->product->id}");
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
                            'onclick' => "adminProductAssoc.delete($model->id)"
                        ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>


</div>