<?php
/**
 * Окно добавления пользователя клиенту
 */
/** @var \app\models\db\Product $product */

/** @var \app\models\db\User $user */
$user = Yii::$app->user->identity;

/** @var \app\components\ClientComponent $client */
$client = Yii::$app->client;
?>
<div class="popup" data-modal id="createAccessModal" style="display:none">
    <div class="popup__cover">
        <form id="userForm" class="popup__block popup__block_form" method="POST">
            <a class="popup__close"
               data-m-target="#createAccessModal"
               data-m-dismiss="modal"
               data-prevent="0">Закрыть
            </a>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Добавить пользователя</h4>
                </div>
            </div>
            <div class="write-form">
                <div class="row">
                    <div hidden>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <input class="write-form__input" name="id">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Email*</label>
                                <input class="write-form__input" name="email" required placeholder="example@mail.ru">
                            </div>
                        </div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Пароль</label>
                                <input class="write-form__input" name="password" placeholder="***" type="password">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">ФИО*</label>
                                <input class="write-form__input" name="fio" required placeholder="Иван Иванов">
                            </div>
                        </div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Телефон*</label>
                                <input class="write-form__input" name="phone" required placeholder="88005553535">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-md-48">
                                <h4 class="popup__title">Права доступа</h4>
                            </div>
                            <div class="col-md-48">
                                <?=
                                \yii\helpers\Html::dropDownList('position', null, \app\models\db\UserToClient::$positions, [
                                    'class' => 'checkout__select'
                                ])
                                ?>
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
        </form>
    </div>
</div>