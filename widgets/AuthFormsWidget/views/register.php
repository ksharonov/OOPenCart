<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var \app\models\form\RegisterForm $model */
?>

<div class="popup" id="authRegister" data-modal style="display:none;">
    <div class="popup__cover">
        <div class="popup__block popup__block_registration">
            <button class="popup__close" data-m-target="#authRegister" data-m-dismiss="modal">Закрыть</button>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Регистрация пользователя</h4>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'action' => '/auth/register',
                'errorCssClass' => '',
                'options' => [
                    'class' => 'registration'
                ]
            ]) ?>
            <div class="row">
                <?= $form->field($model, 'username', [
                    'enableClientValidation' => false,
                    'options' => [
                        'errorCssClass' => 't',
                        'class' => 'registration__formblock'
                    ],
                    'errorOptions' => [
                        'class' => 'help-block'
                    ],
                    'inputOptions' => [
                        'class' => 'registration__input'
                    ],
                    'labelOptions' => [
                        'class' => 'registration__label'
                    ]
                ])->textInput([
                    'autofocus' => true,
                    'placeholder' => 'example@mail.com'
                ]) ?>
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
                <div class="registration__formblock">
                    <div class="check check_login">
                        <input id="check-1" type="checkbox" hidden class="check__input">
                        <label class="check__label" for="check-1">
                            Ознакомлен и согласен с
                            <a href="/docs/220_confidential_policy.pdf" target="_blank" class="registration__policy">
                                политикой конфиденциальности
                            </a>
                        </label>
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" class="registration__submit _register_submit" value="Зарегистрироваться">
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="row _success_register" style="display: none;">
                <br>
                <div class="col-md-48">
                    <p class="login__text text-center">
                        Регистрация прошла успешно!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>