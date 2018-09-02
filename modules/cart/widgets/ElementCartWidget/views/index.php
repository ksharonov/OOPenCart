<?php
use yii\widgets\Pjax;
use app\helpers\NumberHelper;

/** @var \app\models\base\Cart $cart */
?>

<?php Pjax::begin(['timeout' => 10000, 'id' => 'cart', 'options' => ['class' => 'cart']]); ?>
    <div class="cart">
        <h1 class="cart__title">
            Корзина
            <a href="/" data-pjax="0" class="cart__return">Вернуться к покупкам</a>
        </h1>
        <div class="row">
            <div class="col-lg-32 col-md-48">
                <div class="cart-list">
                    <?php foreach ($cart->items as $cartItem) { ?>
                        <div class="cart-list__item" data-product-id="<?= $cartItem->productId ?>">
                            <div class="cart-list__image-wrap">
                                <img src="<?= $cartItem->product->mainImage->link ?? '' ?>" class="cart-list__image">
                            </div>
                            <div class="cart-list__info">
                                <div class="cart-list__title">
                                    <?php if ($cartItem->product->isBest) { ?>
                                        <div class="flags flags_cart clearfix">

                                            <div class="flags__item flags__item_hit">Хит продаж</div>
                                        </div>
                                    <?php } ?>
                                    <a href="<?= $cartItem->product->link ?>"
                                       class="cart-list__link"><?= $cartItem->product->title ?></a>
                                </div>
                                <p class="cart-list__code">Код товара: <?= $cartItem->product->backCode ?></p>
                            </div>
                            <div class="cart-list__control">
                                <div class="regulator regulator_full clearfix">
                                    <button class="regulator__button regulator__button_minus _cart_minus"
                                            data-product-id="<?= $cartItem->productId ?>">
                                        -
                                    </button>
                                    <input type="text" data-model="balance" value="<?= $cartItem->product->balance ?>"
                                           data-product-id="<?= $cartItem->productId ?>" hidden>
                                    <input min="1" max="99" type="text" class="regulator__input"
                                           value="<?= $cartItem->count ?>"
                                           data-model="cart-item"
                                           data-model-of="count"
                                           data-product-id="<?= $cartItem->productId ?>">
                                    <button class="regulator__button regulator__button_plus _cart_plus"
                                            data-product-id="<?= $cartItem->productId ?>">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="cart-list__meta">
                                <div class="cart-list__price">
                                    <span
                                            data-product-id="<?= $cartItem->productId ?>"
                                            data-model="cart-item"
                                            data-model-of="value"
                                    ><?= NumberHelper::asMoney($cartItem->sum) ?></span>
                                    <span class="rouble">₽</span>
                                </div>
                                <input type="text" data-model="price" value=" <?= $cartItem->price ?>"
                                       data-product-id="<?= $cartItem->productId ?>" hidden>
                                <div class="cart-list__count"><span
                                            data-product-id="<?= $cartItem->productId ?>"
                                            data-model="cart-item"
                                            data-model-of="count"
                                    ><?= $cartItem->count ?></span> шт .
                                    x <?= NumberHelper::asMoney($cartItem->price) ?>
                                    <span class="rouble">₽</span>
                                </div>
                            </div>
                            <div class="cart-list__actions">
                                <button class="cart-list__delete _cart_remove"
                                        data-product-id="<?= $cartItem->productId ?>"></button>
                            </div>
                            <div class="clearfix"></div>
                            <?php
                            //                            dump(Yii::$app->cart->discount);
                            //                            dump($cartItem->product->discount->priority);
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="cart__footer">
                    <div class="col-sm-14 col-xs-20">*О ценах и наличии товара уточняйте у менеджеров</div>
                    <div class="col-sm-16 col-sm-offset-18 col-xs-20 col-xs-offset-8 text-right">
                        <span data-model="cart" data-model-of="totalCount"><?= $cart->count ?></span>
                        товаров
                        на
                        <span data-model="cart"
                              data-model-of="totalPrice"><?= NumberHelper::asMoney($cart->sum) ?></span>
                        руб.
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-lg-15 col-lg-offset-1 col-md-48">
                <div class="summary">
                    <div class="summary__sum">
                        <div class="summary__label">Итого</div>
                        <span data-model="cart"
                              data-model-of="totalPrice"><?= NumberHelper::asMoney($cart->sum) ?></span>
                        <span class="rouble">₽</span>
                    </div>
                    <?php if ($cart->getDiscount()) { ?>
                        <div class="summary__sum">
                            <div class="summary__label">Скидка</div>
                            <span data-model="cart"
                                  data-model-of="totalDiscount"><?= NumberHelper::asMoney($cart->getDiscount()) ?></span>
                            <span class="rouble">₽</span>
                        </div>
                    <?php } ?>
                    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->lexemaCard) { ?>
                        <div class="summary__sum">
                            <div class="summary__label">Бонусный баланс</div>
                            <span data-model="cart"
                                  data-model-of="totalBonuses"><?= NumberHelper::asMoney(Yii::$app->user->identity->lexemaCard->bonuses) ?></span>
                            <span class="rouble">₽</span>
                        </div>
                    <?php } ?>
                    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->lexemaDiscountCard) { ?>
                        <div class="summary__sum">
                            <div class="summary__label">Размер скидки</div>
                            <span data-model="cart"
                                  data-model-of="totalDiscountPercent"><?= NumberHelper::asMoney(Yii::$app->user->identity->lexemaDiscountCard->discountValue) ?></span>
                            <span class="rouble">%</span>
                        </div>
                    <?php } ?>
                    <a href="/order/process/"
                       class="summary__order <?= $cart->count < 1 ? "summary__order_disabled" : ""; ?>" data-pjax="0">
                        Оформить заказ
                    </a>
                    <div class="summary__warning">Указанное предложение действительно на <?= date('d.m.Y'); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>