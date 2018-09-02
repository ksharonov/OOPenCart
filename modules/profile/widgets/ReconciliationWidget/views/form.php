<?php

use app\system\base\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use app\models\session\ReconciliationSession;

/* @var ReconciliationSession $model */
?>

<div class="row">
    <div class="reconcillation">
        <?php $form = ActiveForm::begin([
            'id' => 'reconciliation-form',
            'action' => '/api/profile/request-act',
            'method' => 'POST',
            'options' => [
                'data-pjax' => '',
                'onsubmit' => 'return false',
            ]
        ]); ?>
        <div class="col-md-16">
            <?= $form->field($model, 'from')->widget(DatePicker::className(), [
                'name' => 'from',
                'language' => 'ru',
                'dateFormat' => 'php:Y.m.d',
                'options' => [
                    'readonly' => 'readonly',
                    'class' => 'reconcillation__from reconcillation__input',
                    'placeholder' => 'Дата начала'
                ],
            ])->label(false); ?>
        </div>
        <div class="col-md-16">
            <?= $form->field($model, 'to')->widget(DatePicker::className(), [
                'name' => 'to',
                'language' => 'ru',
                'dateFormat' => 'php:Y.m.d',
                'options' => [
                    'readonly' => 'readonly',
                    'class' => 'reconcillation__to reconcillation__input',
                    'placeholder' => 'Дата окончания'
                ],
                'clientOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                ],
            ])->label(false); ?>
        </div>
        <div class="col-md-16">
            <?= \yii\helpers\Html::submitButton('Запросить акт сверки', ['class' => 'entity__act', 'disabled'=>true]); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="popup" id="reconcilationModal" data-modal style="display:none;">
    <div class="popup__cover">
        <div class="popup__block popup__block_apply">
            <button class="popup__close" data-m-target="#reconcilationModal" data-pjax="0"
                    data-m-dismiss="modal">Закрыть
            </button>
            <form class="apply" id="sms" method="POST" enctype="multipart/form-data">
                <div>
                    <h5 class="apply__spec _vacancy_title"></h5>
                    <h6 class="apply__title">Акт сверки</h6>
                </div>
                <div class="apply__block _reconsilation_text">

                </div>
                <div class="apply__block">
                    <input type="button" value="Подтвердить" class="apply__submit btn" data-m-target="#reconcilationModal" data-pjax="0"
                           data-m-dismiss="modal">
                </div>
            </form>
        </div>
    </div>
</div>