<?php
use app\helpers\NumberHelper;

/* @var \app\models\db\Product $product */
//$discount = $product->price->discount;
$discount = false;

if (isset($product->sqlPrice->retail)) {
    $price = $product->sqlPrice->retail;
} else {
    $price = $product->price->individual->value;
}

?>

<!-- НЕ УБИРАТЬ ОБЕРТКУ СТАРОЙ ЦЕНЫ ДАЖЕ ЕСЛИ ЕЁ НЕТ -->
<div class="catalog-product_listview__price-container">
    <div class="catalog-product_listview__price-wrap">
        <div class="catalog-product_listview__price catalog-product_listview__price_old">
            <?php if ($discount && $product->isDiscount) { ?>
                <s><?= $discount->val ?> <span class="rouble">₽</span></s>
            <?php } ?>
        </div>
        <div class="catalog-product_listview__price catalog-product_listview__price_new">
            <input type="text" data-model="price" value="<?= $price ?>"
                   data-product-id="<?= $product->id ?>" hidden>
            <span><?= NumberHelper::asMoney($price) ?></span> <span class="rouble">₽</span>
        </div>
    </div>
</div>
