<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use app\system\base\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\db\OrderStatus;
use app\widgets\form\InlineSelectWidget\InlineSelectWidget;

/* @var $this yii\web\View */
/* @var $model app\models\search\BlockSearch */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'action' => ['/profile/index'],
    'method' => 'GET',
    'options' => ['data-pjax' => true]
]); ?>
<div class="orders__controls clearfix">
    <div class="col-lg-10 col-md-12 col-sm-48">
        <div class="orders-search">
            <?= Html::activeTextInput($model, 'search', [
                'class' => 'orders-search__input',
                'placeholder' => 'Поиск заказа',
                'type' => 'search',
//                'name' => 'search',
            ]); ?>
            <!--            <input class="orders-search__input" type="search" name="search" placeholder="Поиск товара">-->
            <input class="orders-search__submit" type="submit" value="Искать">
        </div>
    </div>
    <div class="col-lg-16 col-lg-offset-1 col-md-22 col-md-offset-2 col-sm-30">
        <div class="text-center flex">
            <button class="orders__action orders__action_copy _order_copy" data-pjax="0">Копировать</button>
<!--            <button class="orders__action orders__action_report"> Cформ . счет</button>-->
            <button class="orders__action orders__action_cancel _order_close" data-pjax="0"> Отменить</button>
        </div>
    </div>
    <div class="col-lg-21 col-md-12 col-sm-18">
        <div class="justify-block visible-lg">

            <?= $form->field($model, 'status')
                ->widget(InlineSelectWidget::className(), [
                    'data' => ArrayHelper::map(OrderStatus::find()
                        ->where(['<>', 'isHidden', true])
                        ->orWhere('isHidden IS NULL')
                        ->all(), 'id', 'title')
                ])
                ->label(false)
            ?>
        </div>
        <div class="hidden-lg text-right">
            <select name="filter" class="orders__selector">
                <option>Все заказы</option>
                <option>В работе</option>
                <option>Завершенные</option>
                <option>Отмененные</option>
            </select>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<!--<div class="block-search">-->
<!---->
<!--    --><?php //$form = ActiveForm::begin([
//        'action' => ['/profile/index'],
//        'method' => 'get',
//        'options' => ['data-pjax' => true]
//    ]); ?>
<!---->
<!--    --><? //= $form->field($model, 'id')
//        ->textInput()
//        ->label('№ заказа') ?>
<!---->
<!--    --><? //= $form->field($model, 'dtCreate')
//        ->label('Дата заказа') ?>
<!---->
<!--    --><?php //$form->field($model, 'status')
//        ->label('Статус')
//        ->dropDownList(ArrayHelper::map(OrderStatus::find()->all(), 'id', 'title')) ?>
<!---->
<!--    --><? //= $form->field($model, 'status')
//        ->widget(InlineSelectWidget::className(), [
//            'data' => ArrayHelper::map(OrderStatus::find()->all(), 'id', 'title')
//        ])
//        ->label(false)
//    ?>
<!---->
<!--    <div class="form-group">-->
<!--        --><? //= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
<!--    </div>-->
<!---->
<!--    --><?php //ActiveForm::end(); ?>
<!---->
<!--</div>-->
