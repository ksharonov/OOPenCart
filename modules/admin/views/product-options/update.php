<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\ProductOptionValue;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var app\models\db\ProductOption $model */
/* @var \yii\debug\models\timeline\DataProvider $dataProviders */

$this->title = 'Редактирование опции: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Опции', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="product-option-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>

    <hr/>
    <?php Pjax::begin(['id' => 'product-option-values']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'value',
                'format' => 'raw',
                'value' => function($optionValue) {
                    /* @var ProductOptionValue $optionValue */
                    $value = $optionValue->value;
                    $id = $optionValue->id;
                    $result = Html::input('text', "value-$id", $value, [
                        'onchange' => "updateOptionValue($id, this.value)",
                        'class' => 'form-control'
                    ]);


                    return $result;
                }
            ],
            [
                'format' => 'raw',
                'value' => function($optionValue) {
                    /* @var ProductOptionValue $optionValue */
                    $id = $optionValue->id;
                    $result = Html::button('Удалить', [
                        'class' => 'btn btn-danger btn-sm',
                        'onclick' => "deleteOptionValue($id)"
                    ]);

                    return $result;

                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <button data-target="#addOptionValueModal" data-toggle="modal" class="btn btn-sm btn-primary">
        Добавить значение
    </button>
    <div class="modal fade" id="addOptionValueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <form onsubmit="return delegateOptionValueCreate(this)">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Добавить значение опции</h4>
                    </div>
                    <div class="modal-body">
                        <h5><?= $model->title; ?></h5>
                        <?= Html::input('text', 'newValue', '', [
                                'class' => 'form-control',
                                'max' => '255',
                                'min' => '1',
                                'required' => 'required'
                            ]);
                        ?>
                        <?= Html::input('hidden', 'optionId', $model->id, ['required' => 'required']); ?>
                    </div>
                    <div class="modal-footer">
                        <input value="Сохранить" type="submit" class="btn btn-primary">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>