<?php

use yii\widgets\DetailView;
use yii\grid\GridView as GVO;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\models\db\ProductAttribute;
use app\modules\admin\helpers\ColorHelper;

/* @var app\models\db\ProductCategory $model */
/* @var app\models\db\Product $productModel */
/* @var \app\models\search\ProductCategorySearch $categorySearchModel */
/* @var \yii\data\ActiveDataProvider $categoryProvider */
?>
<div class="product-category-widget">

    <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#addCategoryModal">
        Добавить категорию
    </button>
    <div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Категории</h4>
                </div>
                <div class="modal-body">
                    <?= GridView::widget([
                        'dataProvider' => $categoryProvider,
                        'filterModel' => $categorySearchModel,
                        'summary' => false,
                        'pjax' => true,
                        'pjaxSettings' => [
                            'timeout' => 100000,
                        ],
                        'columns' => [
                            'id',
                            'title',
                            [
                                'attribute' => 'parent.title',
                                'label' => 'Родитель'
                            ],
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($productModel, $relationModel, $relationId) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'attribute-add btn btn-primary btn-flat btn-sm',
                                        'onclick' => "adminProductCategory.set($productModel->id, $model->id, '$relationModel', '$relationId')"
                                    ]);
                                }
                            ]
                        ],
                    ]); ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(['timeout' => 10000, 'id' => 'categories']); ?>

    <?php
    foreach ($dataProvider->models as $key => $model) {

        if ($key < 10) {
            $colorKey = $key;
        } else {
            $colorKey = null;
        }

        $colorClass = ColorHelper::getRandomColorClass($colorKey);
        ?>
        <button type="button"
                class="categories__item btn <?= $colorClass ?> btn-flat btn-sm margin"
                onclick="adminProductCategory.delete(<?= $productModel->id ?>, <?= $model->categoryId ?>, '<?= $relationModel ?>', '<?= $relationId ?>')">
            <?= $model->category->title ?? null ?>
            <div class="categories__item_delete">
                <i class="fa fa-fw fa-close"></i>
            </div>
        </button>
    <?php }

    if (count($dataProvider->models) === 0) {
        $colorClass = ColorHelper::getRandomColorClass(1);
        ?>
        <button type="button"
                class="categories__item btn <?= $colorClass ?> btn-flat btn-sm margin"
                onclick="" data-toggle="modal" data-target="#addCategoryModal">
            Категория отсутствует
            <div class="categories__item_delete">
                <i class="fa fa-fw fa-close"></i>
            </div>
        </button>
    <?php } ?>


    <?php
    //    GridView::widget([
    //        'dataProvider' => $dataProvider,
    //        'summary' => false,
    //        'columns' => [
    //            'category.title',
    //            [
    //                'attribute' => 'category.parent.title',
    //                'label' => 'Родитель'
    //            ],
    //            [
    //                'label' => 'Действие',
    //                'format' => 'raw',
    //                'value' => function ($model) use ($productModel) {
    //                    return Html::tag('a', 'Удалить', ['href' => '#', 'class' => 'attribute-add btn btn-primary btn-flat btn-sm', 'onclick' => "deleteProductCategory($productModel->id, $model->categoryId)"]);
    //                }
    //            ]
    //        ],
    //    ]);
    ?>
    <?php Pjax::end(); ?>
</div>