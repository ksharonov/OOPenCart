<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\db\ProductCategory;
use app\modules\admin\widgets\product\ProductAttributeWidget\ProductAttributeWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilterFast */
/* @var $form yii\widgets\ActiveForm */
$model->param->getMultiple();
$cities = \app\models\db\CityOnSite::find()->all();
$preCity = [];
foreach ($cities as $city) {
    $preCity[$city->cityId] = $city->city->title;
}

$manuf = \app\models\db\Manufacturer::find()->all();
$preManuf = ArrayHelper::map($manuf, 'id', 'title');
?>

<div class="product-filter-fast-form">

    <div class="" data-example-id="togglable-tabs">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#main" id="main-tab" role="tab" data-toggle="tab"
                   aria-controls="main" aria-expanded="true">Быстрый фильтр</a>
            </li>
            <li role="presentation">
                <a href="#attr" id="attr-tab" role="tab" data-toggle="tab"
                   aria-controls="attr-tab" aria-expanded="false">Атрибуты</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade active in" role="tabpanel" id="main" aria-labelledby="main-tab">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'data-from-translit' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'data-to-translit' => true]) ?>

                <?= $form->field($model, 'categoryId')->dropDownList(ArrayHelper::map(ProductCategory::find()
                    ->select('id, title')
                    ->asArray()
                    ->all(),
                    'id', 'title')) ?>

<!--                --><?//= $form->field($model, 'expanded')->dropDownList([0 => 'Нет', 1 => 'Да']) ?>
                <?php

                ?>
                <h3>
                    Стандартные атрибуты
                </h3>

                <?= $form->field($model, 'params[priceFilter]')
                    ->label(false)
                    ->widget(\unclead\multipleinput\MultipleInput::className(), [
                        'min' => 1,
                        'max' => 1,
                        'allowEmptyList' => true,
                        'value' => $model->param->getAsArray()['priceFilter'] ?? null,
                        'columns' => [
                            [
                                'name' => 'from',
                                'title' => 'Начальная цена',
                                'defaultValue' => null,
                            ],
                            [
                                'name' => 'to',
                                'title' => 'Конечная цена',
                                'enableError' => true,
                                'defaultValue' => null,
                            ]
                        ]
                    ]);
                ?>

                <?= $form->field($model, 'params[manufacturerFilter]')
                    ->label(false)
                    ->widget(\unclead\multipleinput\MultipleInput::className(), [
                        'min' => 1,
                        'max' => 1,
                        'allowEmptyList' => true,
                        'value' => $model->param->getAsArray()['manufacturerFilter'] ?? null,
                        'columns' => [
                            [
                                'name' => 'id]',
                                'type' => 'dropDownList',
                                'title' => 'Производитель',
                                'defaultValue' => null,
                                'items' => $preManuf,
                                'options' => [
                                    'prompt' => 'Выберите производителя...',
                                ]
                            ],
                        ]
                    ]);
                ?>

                <?= $form->field($model, 'params[cityFilter]')
                    ->label(false)
                    ->widget(\unclead\multipleinput\MultipleInput::className(), [
                        'min' => 1,
                        'max' => 1,
                        'allowEmptyList' => true,
                        'value' => $model->param->getAsArray()['cityFilter'] ?? null,
                        'columns' => [
                            [
                                'name' => 'id]',
                                'type' => 'dropDownList',
                                'title' => 'Город',
                                'defaultValue' => null,
                                'items' => $preCity,
                                'options' => [
                                    'prompt' => 'Выберите город...',
                                ]
                            ],
                        ]
                    ]);
                ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success btn-flat btn-sm' : 'btn btn-primary btn-flat btn-sm']) ?>

                    <?php if (!$model->title) {
                        echo Html::a('Отмена', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-sm']);
                    } else {
                        echo Html::a('Отмена', ['index'], ['class' => 'btn btn-danger btn-flat btn-sm']);
                    } ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

            <div class="tab-pane fade" role="tabpanel" id="attr" aria-labelledby="attr-tab">

                <?= ProductAttributeWidget::widget([
                    'relationClass' => \app\models\db\ProductFilterFastParam::className(),
                    'relationPrimaryId' => 'productFilterFastId',
                    'model' => $model
                ]); ?>

            </div>

        </div>
    </div>

</div>
