<?php
use yii\helpers\Html;
use app\models\db\File;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\models\db\Setting;
use app\helpers\ModelRelationHelper;

$data = File::getFileTypesByModelType($productModel->getRelModel());

?>

<div class="product-file-widget">
    <br>
    <div class="row">
        <div class="col-md-1">
            <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal"
                    data-target="#addFileModal">
                Добавить файл
            </button>
        </div>
        <div class="col-md-4">
            <?= Html::dropDownList('product-file-type', null, $data, [
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-md-4">
            <?php if ($productModel->getRelModel() == ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY) { ?>
                <?= Html::textInput('link', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ссылка на банер'
                ]) ?>

            <?php } ?>
        </div>
    </div>
    <br><br>
    <div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width: 80%; height: 80%;">
            <div class="modal-content" style="height: 80%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Атрибуты</h4>
                </div>
                <div class="modal-body" style="height: 80%;">
                    <?= ElFinder::widget([
                        'language' => 'ru',
                        'controller' => 'admin/elfinder',
                        'path' => 'files',
                        'multiple' => false,
                        'containerOptions' => ['style' => 'height: 100%;'],
                        'callbackFunction' => new JsExpression("function(file, id){productFile.add(file, {$productModel->id}, {$productModel->getRelModel()})}")
                    ]); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(['id' => 'files']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-file'],
        'rowOptions' => ['class' => 'product-file__item'],
        'columns' => [
            'id',
            [
                'attribute' => 'path',
                'format' => 'html',
                'value' => function ($model) {
                    $siteUrl = Setting::get('SITE.URL');
                    if (strpos($siteUrl, 'http') === false) {
                        $siteUrl = 'http://' . Setting::get('SITE.URL');
                    }
                    $link = $siteUrl . $model->path;
                    return Html::a($link, $link, [
                        'target' => '_blank',
                        'data-pjax' => 0,
                        'class' => '_to_editor'
                    ]);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return File::$fileStatuses[$model->status];
                }
            ],
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return File::$fileTypes[$model->type];
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'contentOptions' => ['class' => 'product-attributes__actions'],
                'value' => function ($model) use ($productModel) {
                    return
                        Html::tag('a', 'Удалить', [
                            'href' => '#',
                            'class' => 'product-attributes__delete-button btn btn-danger btn-sm',
                            'onclick' => "productFile.remove($model->id)"
                        ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>