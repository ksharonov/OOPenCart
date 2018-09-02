<?php
use app\helpers\NumberHelper;

/* @var \app\models\db\Product $product */
/* цена для юрика */
?>
<div class="catalog-product_listview__price-container">
    <div class="catalog-product_listview__price-wrap catalog-product_listview__price-wrap_entity">
        <div class="catalog-product_listview__price-label">Оптовая цена</div>
        <div class="catalog-product_listview__price catalog-product_listview__price_trade">
            <input type="text" data-model="price"
                   value="<?= $product->sqlPrice->wholesale ?? $product->price->entity->value ?>"
                   data-product-id="<?= $product->id ?>" hidden>
            <span><?= NumberHelper::asMoney($product->sqlPrice->wholesale ?? null) ?? NumberHelper::asMoney($product->price->entity->value) ?></span>
            <span class="rouble">₽</span>
        </div>
    </div>
    <div class="catalog-product_listview__price-wrap">
        <div class="catalog-product_listview__price-label">Розничная цена</div>
        <div class="catalog-product_listview__price catalog-product_listview__price_retail">
<!--            --><?//= NumberHelper::asMoney($product->sqlPrice->retail) ?? NumberHelper::asMoney($product->price->individual->value) ?>
			<?php if (isset($product->sqlPrice->retail) && $product->sqlPrice->retail != '-') { ?>
				<?= NumberHelper::asMoney($product->sqlPrice->retail) ?> <span class="rouble">₽</span>
			<?php } else { ?>
				<?= '-'?>
			<?php } ?>
        </div>
    </div>
</div>