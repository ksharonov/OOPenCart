<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models;
use app\models\db\ProductOptionParam;
use app\models\db\ProductOptionValue;

/* @var app\models\db\Product $productModel */
/* @var app\models\search\ProductOptionParamSearch $optionParamSearchModel */
/* @var \yii\data\ActiveDataProvider $optionParamProvider */
/* @var \yii\data\ActiveDataProvider $dataProvider */
?>
<div class="product-option-param-widget">
    <br>
    <button onclick="editProductOptionParam(<?= $productModel->id; ?>)" type="button" class="btn btn-primary"
            data-toggle="modal" data-target="#addOptionParamModal">
        Добавить вариант
    </button>
    <br><br>
    <div class="modal fade" id="addOptionParamModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Набор опций</h4>
                </div>
                <div id="addOptionParamModalContent"></div>
            </div>
        </div>
    </div>
    <?php Pjax::begin(['timeout' => 10000, 'id' => 'product-option-param']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Вариант',
                'format' => 'html',
                'value' => function ($productParam) use ($productModel) {
                    /* @var ProductOptionParam $productParam */
                    $result = '';
                    foreach ($productParam->optionValues as $optionValue) {
                        /* @var ProductOptionValue $optionValue */
                        $result .= '<b>' . $optionValue->option->title . ':</b> ' . $optionValue->value . '<br/>';
                    }
                    return $result;
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'value' => function ($productParam) use ($productModel) {
                    /* @var ProductOptionParam $productParam */
                    $result = '';
                    $result .= Html::tag('a', 'Изменить', [
                        'href' => '#',
                        'class' => 'option-param-edit btn btn-primary btn-sm',
                        'onclick' => "editProductOptionParam($productModel->id, $productParam->id)",
                        'data-target' => '#addOptionParamModal',
                        'data-toggle' => 'modal',
                    ]);
                    $result .= ' ';
                    $result .= Html::tag('a', 'Удалить', [
                        'href' => '#',
                        'class' => 'option-param-delete btn btn-danger btn-sm',
                        'onclick' => "deleteProductOptionParam($productParam->id)",
                    ]);
                    return $result;
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>