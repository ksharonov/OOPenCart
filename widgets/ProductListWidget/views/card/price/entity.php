<?php

use app\helpers\NumberHelper;

/* @var \app\models\db\Product $product */
/* цена для юрика */
?>

<div class="catalog-product__price-wrap catalog-product__price-wrap_entity">
    <div class="catalog-product__price catalog-product__price_trade">
        <div class="catalog-product__price-label">Оптовая цена</div>
        <div class="catalog-product__price">
            <input type="text" data-model="price"
                   value="<?= $product->sqlPrice->wholesale ?? $product->price->entity->value ?? NumberHelper::asMoney($product->sqlPrice->retail) ?? NumberHelper::asMoney($product->price->individual->value) ?>"
                   data-product-id="<?= $product->id ?>" hidden>
            <span><?= NumberHelper::asMoney($product->sqlPrice->wholesale ?? null) ?? NumberHelper::asMoney($product->price->entity->value) ?? NumberHelper::asMoney($product->sqlPrice->retail) ?? NumberHelper::asMoney($product->price->individual->value) ?></span>
            <span class="rouble">₽</span>
        </div>
    </div>
    <div class="catalog-product__price catalog-product__price_retail">
        <div class="catalog-product__price-label">Розничная цена</div>
        <div class="catalog-product__price">
			<?php if (isset($product->sqlPrice->retail) && $product->sqlPrice->retail != '-') { ?>
				<?= NumberHelper::asMoney($product->sqlPrice->retail) ?> <span class="rouble">₽</span>
			<?php } else { ?>
				<?= '-'?>
			<?php } ?>
        </div>
    </div>
</div>