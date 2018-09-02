<?php

//$this->registerJsFile('https://www.google.com/recaptcha/api.js',  ['position' => yii\web\View::POS_END]);

/**
 * Окно добавления отзыва к товару
 */
/** @var \app\models\db\Product $product */

/** @var \app\models\db\User $user */
$user = Yii::$app->user->identity;

/** @var \app\components\ClientComponent $client */
$client = Yii::$app->client;
if ($user) {
    if ($client->isEntity()) {
        $email = $user->client->email;
        $name = $user->client->title;
    } else {
        $email = $user->email;
        $name = $user->username;
    }
} else {
    $email = null;
    $name = null;
}
?>
<div class="popup" data-modal id="createReviewModal" style="display:none">
    <div class="popup__cover">
        <form id="reviewForm" class="popup__block popup__block_form" method="POST">
            <button class="popup__close"
                    data-m-target="#createReviewModal"
                    data-m-dismiss="modal"
                    data-prevent="0">Закрыть
            </button>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="popup__title">Написать отзыв</h4>
                </div>
            </div>
            <div class="write-form">
                <div class="row">
                    <div class="col-lg-48">
                        <div class="write-form__block">
                            <label class="write-form__label">Оценка</label>
                            <fieldset class="write-form__rating">
                                <input type="radio" id="star5" name="rating" value="5" checked/>
                                <label class="full" for="star5" title="5"></label>
                                <input type="radio" id="star4" name="rating" value="4"/>
                                <label class="full" for="star4" title="4"></label>
                                <input type="radio" id="star3" name="rating" value="3"/>
                                <label class="full" for="star3" title="3"></label>
                                <input type="radio" id="star2" name="rating" value="2"/>
                                <label class="full" for="star2" title="2"></label>
                                <input type="radio" id="star1" name="rating" value="1"/>
                                <label class="full" for="star1" title="1"></label>
                            </fieldset>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-48">
                            <input type="text" name="productId" value="<?= $product->id ?>" hidden>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Email*</label>
                                <input class="write-form__input" name="email" placeholder="example@mail.ru"
                                       required
                                       value="<?= $email ?>">
                            </div>
                        </div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Ваше имя*</label>
                                <input class="write-form__input" name="title" placeholder="Иван Иванов"
                                       required
                                       value="<?= $name ?>">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Преимущества*</label>
                                <textarea class="write-form__input" name="positive"
                                          placeholder="Опишите основные преимущества..."
                                          required
                                          rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <label class="write-form__label">Недостатки*</label>
                                <textarea class="write-form__input" name="negative"
                                          placeholder="Опишите недостатки..."
                                          required
                                          rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-lg-48">
                            <div class="write-form__block">
                                <label class="write-form__label">Комментарий</label>
                                <textarea class="write-form__input" name="comment" placeholder="Прокомментируйте..."
                                          required
                                          rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-48">
                        <div class="alert alert-info disclaimer col-md-offset-1 col-lg-offset-1">
                            <p><b>В отзывах запрещено:</b></p>
                            <ul>
                                <li>Использовать нецензурные выражения, оскорбления и угрозы</li>
                                <li>Публиковать адреса, телефоны и ссылки, содержащие прямую рекламу</li>
                                <li>Писать отвлеченные от темы и бессмысленные комментарии</li>
                            </ul>
                        </div>
                    </div>
<!--                    <div class="col-lg-48">
                        <div class="g-recaptcha" data-sitekey="6LfKE14UAAAAAAj2PXczXb4PsQxGoTImgujN9TB4"></div>
                    </div>-->
                    <div>
                        <div class="col-md-24">
                            <div class="write-form__block">
                                <input type="submit" value="Отправить отзыв" class="write-form__submit">
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