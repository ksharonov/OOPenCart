<?php
use yii\widgets\Pjax;
use app\helpers\NumberHelper;

/* @var \app\models\base\Cart $cart */

?>

<a href="/cart/" class="header__cart">
    <!--    --><?php //Pjax::begin(['timeout' => 10000, 'id' => 'cart-mini']); ?>
    <div>Сумма</div>
    <div class="header__count"><span data-model="cart" data-model-of="totalCount"><?= $cart->count ?></span></div>
    <div class="header__sum"><span data-model="cart"
                                   data-model-of="totalPrice"><?= NumberHelper::asMoney($cart->sum) ?></span> <span
                class="rouble">₽</span></div>
    <!--    --><?php //Pjax::end(); ?>
</a>