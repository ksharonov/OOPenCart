<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models;
use app\models\db\ProductOptionValue;
use app\models\db\ProductOption;
use app\modules\admin\widgets\product\ProductOptionValueSelectorWidget\ProductOptionValueSelectorWidget;

/* @var app\models\db\Product $product */
/* @var app\models\db\ProductOptionSearch $optionSearchModel */
/* @var \yii\data\ActiveDataProvider $optionProvider */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yii\data\ActiveDataProvider $allDataProvider */
?>
<div class="product-option-param-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOptionModal">
        Добавить опцию
    </button>
    <div class="modal fade" id="addOptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Категории</h4>
                </div>
                <div class="modal-body">
                    <?php Pjax::begin(['timeout' => 10000]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $allDataProvider,
                        'columns' => [
                            'id',
                            'title',
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($product) {
                                    /* @var ProductOption $model */
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'attribute-add btn btn-primary btn-flat btn-sm',
                                        'onclick' => "addProductOption($product->id, $model->id)",
                                        'data-dismiss' => 'modal'
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
    <?php Pjax::begin(['timeout' => 10000, 'id' => 'product-option-selector']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Опция',
                'value' => function($productOption) {
                    /* @var ProductOption $productOption */
                    return $productOption->title;
                }
            ],
            [
                'label' => 'Значения',
                'format' => 'raw',
                'value' => function($productOption) use ($product) {
                    /* @var ProductOption $productOption */
                    return ProductOptionValueSelectorWidget::widget([
                        'productOption' => $productOption,
                        'productOptionValues' => $product->optionValues,
                        'product' => $product
                    ]);
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>