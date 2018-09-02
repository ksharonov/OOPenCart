<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var \app\models\form\LoginForm $model */
$message = Yii::$app->session->getFlash('checkUser');
$display = $message ? 'block' : 'none';
?>

<div class="popup" id="authLogin" data-modal style="display:<?= $display ?>;">
    <div class="popup__cover">
        <div class="popup__block popup__block_login">
            <button class="popup__close" data-m-target="#authLogin" data-m-dismiss="modal">Закрыть</button>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Авторизация</h4>
                </div>
            </div>
            <div class="login">
                <div class="row">
                    <div class="col-md-24">
                        <h5 class="login__title">Войти</h5>
                        <p class="login__text">
                            Чтобы копить и использовать бонусы, отслеживать статус заказа в личном кабинете
                        </p>
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'action' => '/auth/login',
                            'options' => [
                                'class' => 'login__form',
                                'data-pjax' => '1'
                            ]
                        ]) ?>

                        <?= $form->field($model, 'username', [
                            'enableClientValidation' => false,
                            'options' => [
                                'class' => 'login__formblock'
                            ],
                            'inputOptions' => [
                                'class' => 'login__input'
                            ],
                            'labelOptions' => [
                                'class' => 'login__label'
                            ]
                        ])->textInput([
                            'autofocus' => true,
                            'placeholder' => 'example@mail.com'
                        ]) ?>

                        <?= $form->field($model, 'password', [
                            'enableClientValidation' => false,
                            'options' => [
                                'class' => 'login__formblock'
                            ],
                            'inputOptions' => [
                                'class' => 'login__input',
                                'type' => 'password'
                            ],
                            'labelOptions' => [
                                'class' => 'login__label'
                            ]
                        ])->textInput([
                            'autofocus' => true,
                            'placeholder' => '***'
                        ]) ?>

                        <div class="login__footer">
                            <div class="check check_login">
                                <input id="rememberMe" name="LoginForm[rememberMe]" type="checkbox" hidden
                                       class="check__input">
                                <label class="check__label" for="rememberMe">Запомнить меня</label>
                            </div>
                            <?= Html::submitButton('Войти', ['class' => 'login__submit _login_submit']) ?>

                        </div>
                        <?php ActiveForm::end(); ?>

                        <h5 class="login__title"><?= $message ?? '&nbsp;' ?></h5>

                    </div>
                    <div class="col-md-22 col-md-offset-2">
                        <h5 class="login__title">Нет учетной записи?</h5>
                        <p class="login__text">
                            Зарегистрируйтесь на нашем сайте
                        </p>
                        <a href="#" class="login__register" data-m-toggle="modal" data-m-target="#authRegister">Регистрация</a>
                        <br><br><br>
                        <h5 class="login__title">Забыли пароль?</h5>
                        <p class="login__text">
                            Пройдите по ссылке и получите новый
                        </p>
                        <a href="#" class="login__register" data-m-toggle="modal" data-m-target="#authRestore">Восстановить</a>
                    </div>
                </div>
            </div>
            <div class="row _error_login" style="display: none;">
                <br>
                <div class="col-md-48">
                    <p class="login__text text-center">
                        Неверный логин или пароль!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>