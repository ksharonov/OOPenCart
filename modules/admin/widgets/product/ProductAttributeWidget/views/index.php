<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\db\ProductAttribute;

/* @var $this yii\web\View */
/* @var app\models\db\ProductAttribute $model */
/* @var app\models\db\Product $productModel */
/* @var app\models\search\ProductAttributeSearch $attributeSearchModel */
/* @var yii\data\ActiveDataProvider $attributesProvider */
?>

<div class="product-attribute-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAttributeModal">
        Добавить атрибут
    </button>

    <button type="button" class="btn btn-primary" data-target="#saveAttributes"
            onclick="adminProductAttribute.save(<?= $productModel->id ?>)">
        Сохранить
    </button>
    <br><br>


    <div class="modal fade" id="addAttributeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        'dataProvider' => $attributesProvider,
                        'filterModel' => $attributeSearchModel,
                        'summary' => false,
                        'columns' => [
                            'id',
                            'title',
                            'group.title',
                            [
                                'label' => 'Значение',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (in_array($model->type, [
                                        ProductAttribute::TYPE_FLAG_MANY,
                                        ProductAttribute::TYPE_SELECT_TEXT,
                                        ProductAttribute::TYPE_SELECT_NUMBER
                                    ])) {
                                        $jsonParams = is_array($model->params) ? $model->params : \yii\helpers\Json::decode($model->params);
                                        $retArray = [];

                                        foreach ($jsonParams as $key => $value) {
                                            $retArray[$value] = $value;
                                        }

                                        return Html::dropDownList('value', [], $retArray, [
                                            'class' => 'form-control'
                                        ]);
                                    } else {
                                        return Html::input('string', 'value', null, [
                                            'class' => 'form-control'
                                        ]);
                                    }
                                }
                            ],
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($productModel) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'attribute-add btn btn-primary btn-sm',
                                        'onclick' => "adminProductAttribute.set($productModel->id, $model->id)"
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

    <?php Pjax::begin(['timeout' => 10000, 'id' => 'attributes']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-attributes'],
        'rowOptions' => ['class' => 'product-attributes__item'],
        'columns' => [
            [
                'label' => 'Группа',
                'group' => true,
                'groupedRow' => true,
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'product-attributes__header'];
                },
                'value' => function ($model) {
                    return $model->attr->group->title ?? "Нет группы";
                }
            ],
            [
                'label' => 'Атрибут',
                'contentOptions' => ['class' => 'product-attributes__title'],
                'value' => function ($model) {
                    return $model->attr->title;
                }
            ],
            [
                'label' => 'Значение',
                'format' => 'raw',
                'contentOptions' => ['class' => 'product-attributes__value'],
                'value' => function ($model) use ($productModel) {

                    if (in_array($model->attr->type, [
                        ProductAttribute::TYPE_FLAG_MANY,
                        ProductAttribute::TYPE_SELECT_TEXT,
                        ProductAttribute::TYPE_SELECT_NUMBER
                    ])) {
                        $jsonParams = \yii\helpers\Json::decode($model->attr->params);

                        $retArray = [];

                        foreach ($jsonParams as $key => $value) {
                            $retArray[$value] = $value;
                        }

                        return Html::dropDownList('value', $model->attrValue, $retArray, [
                            'class' => 'form-control product-attributes__value-item',
                            'data-attr-id' => $model->attr->id,
                            'data-product-id' => $productModel->id
                        ]);
                    } else {
                        return Html::input('string', 'value', $model->attrValue, [
                            'class' => 'form-control product-attributes__value-item',
                            'data-attr-id' => $model->attr->id,
                            'data-product-id' => $productModel->id
                        ]);
                    }
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'contentOptions' => ['class' => 'product-attributes__actions'],
                'value' => function ($model) use ($productModel) {
                    return
                        Html::tag('a', 'Сохранить', [
                            'href' => '#',
                            'class' => 'product-attributes__add-button btn btn-primary btn-sm',
                            'onclick' => "adminProductAttribute.inlineSet($productModel->id, $model->attributeId)"
                        ]) .
                        Html::tag('a', 'Удалить', [
                            'href' => '#',
                            'class' => 'product-attributes__delete-button btn btn-danger btn-sm',
                            'onclick' => "adminProductAttribute.delete($productModel->id, $model->attributeId)"
                        ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>