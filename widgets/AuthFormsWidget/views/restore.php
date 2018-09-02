<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var \app\models\form\RegisterForm $model */
?>

<div class="popup" id="authRestore" data-modal style="display:none;">
    <div class="popup__cover">
        <div class="popup__block popup__block_registration">
            <button class="popup__close" data-m-target="#authRestore" data-m-dismiss="modal">Закрыть</button>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Восстановление пароля</h4>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'restore-form',
                'action' => '/auth/restore-password',
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
                <div class="text-center">
                    <input type="submit" class="registration__submit _restore_submit" value="Отправить пароль">
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div class="row _success_restore" style="display: none;">
                <br>
                <div class="col-md-48">
                    <p class="login__text text-center">
                        Сообщение было отправлено!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>