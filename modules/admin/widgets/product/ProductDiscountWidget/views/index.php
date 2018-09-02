<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\db\ProductAttribute;
use app\models\db\Discount;

/* @var \app\models\db\Discount $formModel */
/* @var app\models\db\ProductAttribute $model */
/* @var app\models\db\Product $relModel */
/* @var app\models\search\ProductAttributeSearch $discountSearchModel */
/* @var yii\data\ActiveDataProvider $discountProvider */
?>

<div class="product-analogue-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDiscountModal">
        Добавить скидку
    </button>
    <br><br>


    <div class="modal fade" id="addDiscountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Скидки</h4>
                </div>
                <div class="modal-body">
                    <?php
                    $form = \yii\widgets\ActiveForm::begin(['id' => 'newDiscountForm']);

                    echo $form->field($formModel, 'title')->textInput();
                    echo $form->field($formModel, 'status')->dropDownList(\app\models\db\Discount::$statuses);
                    echo $form->field($formModel, 'type')->dropDownList(\app\models\db\Discount::$types);
                    echo $form->field($formModel, 'value')->textInput();
                    echo $form->field($formModel, 'relModel')->hiddenInput()->label(false);
                    echo $form->field($formModel, 'relModelId')->hiddenInput()->label(false);

                    \yii\widgets\ActiveForm::end() ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success _set_discount" data-dismiss="modal">
                        Добавить
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


    <?php Pjax::begin(['timeout' => 10000, 'id' => 'discounts']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-discounts'],
        'rowOptions' => ['class' => 'product-discounts__item'],
        'columns' => [
            [
                'attribute' => 'title'
            ],
            [
                'attribute' => 'type',
                'value' => function (Discount $model) {
                    return Discount::$types[$model->type];
                }
            ],
            [
                'attribute' => 'status',
                'value' => function (Discount $model) {
                    return Discount::$statuses[$model->status] ?? '';
                }
            ],
            [
                'attribute' => 'value'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '/admin/discount/delete?id=' . $model->id,
                            [
                                'title' => Yii::t('yii', 'Create'),
                            ]
                        );

                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>


</div>