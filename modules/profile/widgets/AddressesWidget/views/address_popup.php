<?php
/**
 * Окно добавления пользователя клиенту
 */

use yii\widgets\ActiveForm;

/** @var \app\models\db\Product $product */

/** @var \app\models\db\User $user */
$user = Yii::$app->user->identity;

/** @var \app\components\ClientComponent $client */
$client = Yii::$app->client;
?>
<div class="popup" data-modal id="createAddressModal" style="display:none">
    <div class="popup__cover">
        <!--        <form id="addressForm" class="popup__block popup__block_form" method="POST">-->
        <?php $form = ActiveForm::begin([
            'id' => 'addressForm',
            'action' => '',
            'method' => 'GET',
            'errorCssClass' => '',
            'options' => [
                'class' => 'popup__block popup__block_form'
            ]
        ]) ?>

        <a class="popup__close"
           data-m-target="#createAddressModal"
           data-m-dismiss="modal"
           data-prevent="0">Закрыть
        </a>
        <div class="row">
            <div class="col-md-48">
                <h4 class="popup__title">Добавить адрес</h4>
            </div>
        </div>
        <div class="write-form">
            <div class="row">
                <div hidden>
                    <input class="write-form__input" name="id">
                </div>
                <div>
                    <div class="col-md-24">
                        <div class="write-form__block">
                            <label class="write-form__label">Страна*</label>
                            <input class="write-form__input" name="country" required placeholder="н-р Россия">
                        </div>
                    </div>
                    <div class="col-md-24">
                        <div class="write-form__block">
                            <label class="write-form__label">Город*</label>
                            <input class="write-form__input" name="city" required placeholder="н-р Уфа">
                        </div>
                    </div>
                </div>
                <div>
                    <div class="col-md-24">
                        <div class="write-form__block">
                            <label class="write-form__label">Почтовый индекс*</label>
                            <input class="write-form__input" name="postcode" required placeholder="450000">
                        </div>
                    </div>
                    <div class="col-md-24">
                        <div class="write-form__block">
                            <label class="write-form__label">Адрес*</label>
                            <input class="write-form__input" name="address" required placeholder="Улица...">
                        </div>
                    </div>
                </div>
                <div>
                    <div class="col-md-24">
                        <div class="write-form__block">
                            <input type="submit" value="Добавить" class="write-form__submit">
                        </div>
                    </div>
                </div>
                <div class="col-md-24">
                    <p class="write-form__warning">* - поля, обязательные для заполнения</p>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <!--        </form>-->
    </div>
</div>