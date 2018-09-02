<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\cookie\CityCookie;

$citySelected = CityCookie::get()->city->title ?? null;
/* @var \yii\web\View $this */
/* @var \app\models\db\Order $order */
/* @var array $extensionParams */
/* @var \app\extensions\delivery\DeliveryHomeExtension\models\WidgetModel $model */
?>

<div>
    <div class="delivery-form__inputs">
        <div class="delivery-form__group clearfix">
            <div class="checkout__cols">
                <?= $form->field($model, "city", [
                    'options' => [
                        'class' => 'clearfix',
                    ],
                    'labelOptions' => [
                        'class' => 'checkout__label'
                    ],
                    'inputOptions' => [
                        'class' => 'checkout__input',
                        'value' => $citySelected,
//                        'disabled' => (bool)$citySelected
                    ]])
                    ->textInput();
                ?>
            </div>
            <div class="checkout__cols">
                <?= $form->field($model, "street", [
                    'options' => [
                        'class' => 'checkout__col'
                    ],
                    'labelOptions' => [
                        'class' => 'checkout__label'
                    ],
                    'inputOptions' => [
                        'class' => 'checkout__input'
                    ]])
                    ->textInput();
                ?>

                <?= $form->field($model, "home", [
                    'options' => [
                        'class' => 'checkout__col'
                    ],
                    'labelOptions' => [
                        'class' => 'checkout__label'
                    ],
                    'inputOptions' => [
                        'class' => 'checkout__input'
                    ]])
                    ->textInput();
                ?>

                <?= $form->field($model, "room", [
                    'options' => [
                        'class' => 'checkout__col'
                    ],
                    'labelOptions' => [
                        'class' => 'checkout__label'
                    ],
                    'inputOptions' => [
                        'class' => 'checkout__input'
                    ]])
                    ->textInput();
                ?>
            </div>
            <?= $form->field($model, "comment", [
                'options' =>
                    [
                        'class' => 'clearfix'
                    ],
                'labelOptions' => [
                    'class' => 'checkout__label'
                ],
                'inputOptions' => [
                    'class' => 'checkout__input'
                ]])
                ->textInput()
                ->label("Комментарий");
            ?>
            <div class="clearfix">
                <p class="delivery-form__text">
                    *Для утоочнения времени и стоимости доставки с вами
                    свяжется наш менджер
                </p>
            </div>
        </div>
    </div>

</div>