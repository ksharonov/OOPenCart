<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\widgets\product\ProductAttributeWidget\ProductAttributeWidget;
use app\modules\admin\widgets\product\ProductCategoryWidget\ProductCategoryWidget;
use app\modules\admin\widgets\product\ProductPriceWidget\ProductPriceWidget;
use app\modules\admin\widgets\product\ProductOptionParamWidget\ProductOptionParamWidget;
use app\modules\admin\widgets\product\ProductAnalogueWidget\ProductAnalogueWidget;
use app\modules\admin\widgets\product\ProductOptionWidget\ProductOptionWidget;
use app\modules\admin\widgets\product\ProductImageWidget\ProductImageWidget;
use app\modules\admin\widgets\product\ProductUnitWidget\ProductUnitWidget;
use app\modules\admin\widgets\product\ProductAssociatedWidget\ProductAssociatedWidget;
use dosamigos\tinymce\TinyMce;
use app\models\db\Product;
use kartik\select2\Select2;
use app\models\db\Manufacturer;
use yii\helpers\ArrayHelper;
use app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;
use app\modules\admin\widgets\common\SeoEditWidget\SeoEditWidget;
use app\models\db\ProductToCategory;
use app\modules\admin\widgets\product\ProductDiscountWidget\ProductDiscountWidget;

$pricesIsNull = \app\models\db\ProductPriceGroup::find()->count();
$unitIsNull = \app\models\db\ProductUnit::find()->count();

$defaultData = ArrayHelper::map(
    Manufacturer::find()
        ->select('id, title')
        ->where(['id' => $model->manufacturerId])
        ->all(),
    'id', 'title');

/* @var $this yii\web\View */
/* @var $model app\models\db\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', [
        'id' => 'preProductSubmit',
        'class' => $model->isNewRecord ?
            'btn btn-primary btn-flat btn-sm' :
            'btn btn-primary btn-flat btn-sm']) ?>
    <?php if (!$model->title) {
        echo Html::a('Отмена', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-sm', 'id' => 'preProductCancel']);
    } else {
        echo Html::a('Отмена', ['index'], ['class' => 'btn btn-danger btn-flat btn-sm', 'id' => 'preProductCancel']);
    } ?>
    <br><br>
    <div class="" data-example-id="togglable-tabs">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#main" id="main-tab" role="tab" data-toggle="tab"
                   aria-controls="main" aria-expanded="true">Товар</a>
            </li>
            <!--            <li role="presentation">-->
            <!--                <a href="#category" id="category-tab" role="tab" data-toggle="tab"-->
            <!--                   aria-controls="category" aria-expanded="true">Категории</a>-->
            <!--            </li>-->
            <li role="presentation">
                <a href="#default-attr" id="default-attr-tab" role="tab" data-toggle="tab"
                   aria-controls="default-attr" aria-expanded="false">Стандартные атрибуты</a>
            </li>
            <li role="presentation" class="">
                <a href="#image" role="tab" id="image-tab" data-toggle="tab"
                   aria-controls="image" aria-expanded="false">Изображения</a>
            </li>
            <li role="presentation" class="">
                <a href="#file" role="tab" id="file-tab" data-toggle="tab"
                   aria-controls="file" aria-expanded="false">Файлы</a>
            </li>
            <?php if ($pricesIsNull > 1) { ?>
                <li role="presentation" class="">
                    <a href="#cost" role="tab" id="cost-tab" data-toggle="tab"
                       aria-controls="cost" aria-expanded="false">Прайсы</a>
                </li>
            <?php } ?>
            <li role="presentation" class="">
                <a href="#attribute" role="tab" id="attribute-tab" data-toggle="tab"
                   aria-controls="attribute" aria-expanded="false">Атрибуты</a>
            </li>
            <!--            <li role="presentation" class="">-->
            <!--                <a href="#option" role="tab" id="option-tab" data-toggle="tab"-->
            <!--                   aria-controls="option" aria-expanded="false">Опции</a>-->
            <!--            </li>-->
            <!--            <li role="presentation" class="">-->
            <!--                <a href="#variant" role="tab" id="option-tab" data-toggle="tab"-->
            <!--                   aria-controls="variants" aria-expanded="false">Варианты товара</a>-->
            <!--            </li>-->
            <li role="presentation" class="">
                <a href="#analogue" role="tab" id="analogue-tab" data-toggle="tab"
                   aria-controls="analogue" aria-expanded="false">Аналоги</a>
            </li>
            <li role="presentation" class="">
                <a href="#associated" role="tab" id="associated-tab" data-toggle="tab"
                   aria-controls="associated" aria-expanded="false">Соответствия</a>
            </li>
            <?php if ($unitIsNull >= 0) { ?>
                <li role="presentation" class="">
                    <a href="#unit" role="tab" id="unit-tab" data-toggle="tab"
                       aria-controls="unit" aria-expanded="false">Единицы измерения</a>
                </li>
            <?php } ?>
            <li role="presentation" class="">
                <a href="#seo" id="seo-tab" role="tab" data-toggle="tab"
                   aria-controls="seo" aria-expanded="true">SEO</a>
            </li>
            <li role="presentation" class="">
                <a href="#discount" id="discount-tab" role="tab" data-toggle="tab"
                   aria-controls="discount" aria-expanded="true">Скидки</a>
            </li>
            <li role="presentation" class="">
                <a href="#reviews" id="reviews-tab" role="tab" data-toggle="tab"
                   aria-controls="reviews" aria-expanded="true">Отзывы</a>
            </li>
        </ul>


        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade active in" role="tabpanel" id="main" aria-labelledby="main-tab">
                <br>
                <?= ProductCategoryWidget::widget(['model' => $model]); ?>

                <?php Pjax::begin(['timeout' => 10000, 'id' => 'product']); ?>
                <?php $form = ActiveForm::begin(['options' => ['id' => 'product-form']]); ?>

                <?= $form->field($model, 'vendorCode')->textInput(['maxlength' => true, 'disabled' => true]) ?>

                <?= $form->field($model, 'backCode')->textInput(['maxlength' => true, 'disabled' => true]) ?>

                <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'data-from-translit' => true]) ?>

                <?= $form->field($model, 'slug')->textInput(['data-to-translit' => true]) ?>

                <?= $form->field($model, 'manufacturerId')->widget(Select2::classname(), [
                    'data' => $defaultData,
                    'language' => 'ru',
                    'options' => [
                        'placeholder' => 'Выбрать производителя',
                        'id' => 'manufacturer',
                        'style' => 'width: 100%;'
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => false,
                        'query' => new \yii\web\JsExpression('function(query) { getManufacturers(query) }'),
                    ]
                ]); ?>

                <?= $form->field($model, 'manyAmount')->textInput(['maxlength' => true])->label('Количество для "Много" (минимальное количество товаров в наличии, после которого отображается слово "Много")') ?>

                <?php if ($model->title !== null) { ?>
                    <?= $form->field($model, 'status')->dropDownList(Product::$statuses) ?>
                <?php } ?>

                <?= $form->field($model, 'defaultPrice')->textInput(['maxlength' => true, 'value' => $model->price->default->value ?? null]) ?>

                <?= $form->field($model, 'smallDescription')->textInput(['maxLength' => true]); ?>



                <?= $form->field($model, 'content')->widget(TinyMce::className(), ['options' => ['rows' => 20],
                    'language' => 'ru',
                    'clientOptions' => [
                        'invalid_styles' => [
                            'table' => 'height width',
                            'td' => 'height width',
                            'tr' => 'height width',
                            'th' => 'height width'
                        ],
                        'valid_classes' => [
                            '*' => 'class',
                        ],
                        'plugins' => ["advlist autolink lists link charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste"],
                        'toolbar' => "undo redo | styleselect | bold italic |
                         alignleft aligncenter alignright alignjustify |
                          bullist numlist outdent indent | link"]]) ?>

                <div class="form-group" hidden>
                    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', [
                        'id' => 'productSubmit',
                        'class' => $model->isNewRecord ?
                            'btn btn-primary btn-flat btn-sm' :
                            'btn btn-primary btn-flat btn-sm']) ?>
                    <?php if (!$model->title) {
                        echo Html::a('Отмена', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-sm', 'id' => 'productCancel']);
                    } else {
                        echo Html::a('Отмена', ['index'], ['class' => 'btn btn-danger btn-flat btn-sm', 'id' => 'productCancel']);
                    } ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?php Pjax::end(); ?>
            </div>

            <div class="tab-pane fade" role="tabpanel" id="default-attr" aria-labelledby="default-attr-tab">
                <br>

                <div class="row">
                    <!--                    <div class="col-md-4">-->
                    <!--                        --><? //= $form->field($model, 'isBest')->checkbox() ?>
                    <!--                    </div>-->
                    <!--                    <div class="col-md-4">-->
                    <!--                        --><? //= $form->field($model, 'isNew')->checkbox() ?>
                    <!--                    </div>-->
                    <!--                    <div class="col-md-4">-->
                    <!--                        --><? //= $form->field($model, 'isDiscount')->checkbox() ?>
                    <!--                    </div>-->
                </div>
                <?= DynamicParamsWidget::widget([
                    'model' => $model,
                    'attribute' => 'params'
                ]); ?>
            </div>
            <div class="tab-pane fade" role="tabpanel" id="category" aria-labelledby="category-tab">

            </div>
            <div class="tab-pane fade" role="tabpanel" id="image" aria-labelledby="image-tab">
                <?= ProductImageWidget::widget(['model' => $model]); ?>
            </div>

            <div class="tab-pane fade" role="tabpanel" id="file" aria-labelledby="file-tab">
                <?= ProductFileWidget::widget(['model' => $model]); ?>
            </div>
            <?php if ($pricesIsNull >= 0) { ?>
                <div class="tab-pane fade" role="tabpanel" id="cost" aria-labelledby="cost-tab">

                    <?= ProductPriceWidget::widget(['model' => $model]);
                    ?>
                </div>
            <?php } ?>

            <div class="tab-pane fade" role="tabpanel" id="attribute" aria-labelledby="attribute-tab">

                <?= ProductAttributeWidget::widget(['model' => $model]); ?>

            </div>
            <!--            <div class="tab-pane fade" role="tabpanel" id="option" aria-labelledby="option-tab">-->
            <!--                <div>-->
            <!--                    --><? //= ProductOptionWidget::widget(['model' => $model,]); ?>
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="tab-pane fade" role="tabpanel" id="variant" aria-labelledby="option-tab">-->
            <!--                <div>-->
            <!--                    --><? //= ProductOptionParamWidget::widget(['model' => $model,]); ?>
            <!--                </div>-->
            <!--            </div>-->
            <div class="tab-pane fade" role="tabpanel" id="analogue" aria-labelledby="analogue-tab">

                <?= ProductAnalogueWidget::widget(['model' => $model]); ?>

            </div>

            <div class="tab-pane fade" role="tabpanel" id="associated" aria-labelledby="associated-tab">

                <?= ProductAssociatedWidget::widget(['model' => $model]); ?>

            </div>

            <div class="tab-pane fade" role="tabpanel" id="unit" aria-labelledby="unit-tab">

                <?= ProductUnitWidget::widget(['model' => $model]); ?>

            </div>

            <div class="tab-pane fade" role="tabpanel" id="seo" aria-labelledby="seo-tab">
                <br>
                <?= SeoEditWidget::widget(['model' => $model]); ?>

            </div>
            <div class="tab-pane fade" role="tabpanel" id="discount" aria-labelledby="discount-tab">
                <br>
                <?= ProductDiscountWidget::widget(
                    [
                        'relModel' => $model->relModel,
                        'relModelId' => $model->id
                    ]
                ); ?>

            </div>

            <div class="tab-pane fade" role="tabpanel" id="reviews" aria-labelledby="reviews-tab">
                <br>
                <?= \app\modules\admin\widgets\product\ProductReviewsWidget\ProductReviewsWidget::widget(
                    ['model' => $model]
                ); ?>

            </div>
        </div>


    </div>

</div>
