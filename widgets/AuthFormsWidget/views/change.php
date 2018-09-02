<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var \app\models\form\RegisterForm $model */
/* @var string $key */
$displayAuth = $key ? 'block' : 'none';
?>

<div class="popup" id="authChange" data-modal style="display:<?= $displayAuth ?>;">
    <div class="popup__cover">
        <div class="popup__block popup__block_registration">
            <button class="popup__close" data-m-target="#authChange" data-m-dismiss="modal">Закрыть</button>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Восстановление пароля</h4>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'change-form',
                'action' => '/auth/change-password',
                'errorCssClass' => '',
                'options' => [
                    'class' => 'registration'
                ]
            ]) ?>
            <div class="row">
                <?= $form->field($model, 'password', [
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'registration__formblock'
                    ],
                    'inputOptions' => [
                        'class' => 'registration__input'
                    ],
                    'labelOptions' => [
                        'class' => 'registration__label'
                    ]
                ])->passwordInput([
                    'autofocus' => true,
                    'placeholder' => '***'
                ]) ?>
                <?= $form->field($model, 'key')->hiddenInput([
                    'value' => $key
                ])->label(false) ?>
                <div class="text-center">
                    <input type="submit" class="registration__submit" value="Отправить пароль">
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
