<?php

use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;

/* @var app\models\db\ProductPrice $model */
/* @var app\models\db\Product $productModel */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var app\models\db\ProductPriceSearch $priceSearchModel */
?>
<div class="product-price-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPriceModal">
        Добавить цену
    </button>
    <br><br>
    <div class="modal fade" id="addPriceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        'dataProvider' => $priceProvider,
                        'filterModel' => $priceSearchModel,
                        'summary' => false,
                        'columns' => [
                            'title',
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($productModel) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'attribute-add btn btn-primary btn-sm',
                                        'onclick' => "setProductPrice($productModel->id, $model->id)"
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

    <?php Pjax::begin(['timeout' => 10000, 'id' => 'prices']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-price'],
        'rowOptions' => ['class' => 'product-price__item'],
        'columns' => [
            [
                'attribute' => 'productPriceGroup.title',
                'label' => 'Группа цены',
                'group' => true,
                'groupedRow' => true
            ],
            [
                'attribute' => 'value',
                'label' => 'Значение',
                'format' => 'raw',
                'value' => function ($model) use ($productModel) {
                    return Html::input('string', 'value', $model->value, [
                        'class' => 'product-attributes__value form-control',
                        'data-product-price-id' => $model->id]);
                }
            ],
//            [
//                'attribute' => 'dtStart',
//                'content' => function ($model) {
//                    return DateTimePicker::widget([
//                        'model' => $model,
//                        'attribute' => 'dtStart',
//                        'name' => "dSt_$model->id",
//                        'options' => [
//                            'placeholder' => 'Дата',
//                            'id' => "dSt_$model->id"
//                        ],
//                        'convertFormat' => true,
//                        'pluginOptions' => [
//                            'autoclose' => true,
//                            'todayHighlight' => true,
//                            'minuteStep' => 15,
//                            'format' => 'dd.MM.yy hh:i',
//                        ],
//                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
//                        'size' => 'md',
//                        'value' => date('d.M.y H:i', $model->dtStart),
//                    ]);
//                }
//            ],
//            [
//                'attribute' => 'dtEnd',
//                'content' => function ($model) {
//                    return DateTimePicker::widget([
//                        'model' => $model,
//                        'attribute' => 'dtEnd',
//                        'name' => "dEn_$model->id",
//                        'options' => [
//                            'placeholder' => 'Дата',
//                            'id' => "dEn_$model->id"
//                        ],
//                        'convertFormat' => true,
//                        'pluginOptions' => [
//                            'autoclose' => true,
//                            'todayHighlight' => true,
//                            'minuteStep' => 15,
//                            'format' => 'dd.MM.yy hh:ii',
//                        ],
//                        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
//                        'size' => 'md',
//                        'value' => date('d.M.y H:i', $model->dtEnd),
//                    ]);
//                }
//            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'contentOptions' => ['class' => 'product-attributes__actions'],
                'value' => function ($model) use ($productModel) {
                    return
                        Html::tag('a', 'Сохранить', [
                            'href' => '#',
                            'class' => 'product-attributes__add-button btn btn-primary btn-sm',
                            'onclick' => "inlineSetProductPrice($model->id)"
                        ]) .
                        Html::tag('a', 'Удалить', [
                            'href' => '#',
                            'class' => 'product-attributes__delete-button btn btn-danger btn-sm',
                            'onclick' => "deleteProductPrice($model->id)"]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>