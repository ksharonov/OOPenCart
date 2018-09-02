<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\db\User;
use app\widgets\SmsPopupWidget\SmsPopupWidget;

/** @var User $model */

$display = !is_null($model) ? 'block' : 'none';

echo SmsPopupWidget::widget();
?>
<div class="popup" id="userEdit" data-modal style="display:none;">
    <div class="popup__cover">
        <div class="popup__block popup__block_registration">
            <a href="#" class="popup__close" data-m-target="#userEdit" data-pjax="0" data-m-dismiss="modal">Закрыть</a>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Редактирование пользователя</h4>
                    <?php
                    $form = ActiveForm::begin([
                        'options' => [
                            'data-pjax' => true,
                            'id' => 'userEditForm'
                        ]
                    ]);
                    echo $form->field($model, 'fio', [
                        'enableClientValidation' => false,
                        'options' => [
                            'class' => 'login__formblock'
                        ],
                        'inputOptions' => [
                            'class' => 'login__input',
                            'type' => 'text'
                        ],
                        'labelOptions' => [
                            'class' => 'login__label'
                        ]
                    ]);
                    echo $form->field($model, 'phone', [
                        'enableClientValidation' => false,
                        'options' => [
                            'class' => 'login__formblock'
                        ],
                        'inputOptions' => [
                            'class' => 'login__input',
                            'type' => 'text'
                        ],
                        'labelOptions' => [
                            'class' => 'login__label'
                        ]
                    ]);

                    echo $form->field($model, 'password', [
                        'enableClientValidation' => false,
                        'options' => [
                            'class' => 'login__formblock'
                        ],
                        'inputOptions' => [
                            'class' => 'login__input',
                            'type' => 'password',
                            'value' => '',
                            'placeholder' => '***'
                        ],
                        'labelOptions' => [
                            'class' => 'login__label'
                        ]
                    ]);

                    echo $form->field($model, 'repeatPassword', [
                        'enableClientValidation' => false,
                        'options' => [
                            'class' => 'login__formblock'
                        ],
                        'inputOptions' => [
                            'class' => 'login__input',
                            'type' => 'password',
                            'value' => '',
                            'placeholder' => '***'
                        ],
                        'labelOptions' => [
                            'class' => 'login__label'
                        ]
                    ]);

                    echo $form->field($model, 'email', [
                        'enableClientValidation' => false,
                        'options' => [
                            'class' => 'login__formblock'
                        ],
                        'inputOptions' => [
                            'class' => 'login__input',
                            'type' => 'text',
                            'disabled' => true
                        ],
                        'labelOptions' => [
                            'class' => 'login__label'
                        ]
                    ]);
                    ?>
                    <?= Html::submitButton('Сохранить', [
                        'class' => 'btn'
                    ]) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
