<?php
use yii\helpers\Html;
return;
/* @var \app\models\db\Product $model */
?>

<div class="catalog-product">
    <div class="catalog-product__image-wrap catalog-product__image-wrap_loading">
        <img data-src="<?= $model->mainImage->path ?? '' ?>" class="catalog-product__image">
    </div>
    <div class="catalog-product__flags"></div>
    <h3 class="catalog-product__title">
        <a href="<?= $model->link ?>" class="catalog-product__link" data-pjax="0" title="<?= $model->title ?>">
            <?= $model->title ?>
        </a>
    </h3>
    <div class="catalog-product__misc">
        <div class="catalog-product__producer" title="<?= $model->manufacturer->title ?? "Н/Д" ?>">
            <?= $model->manufacturer->title ?? "Н/Д" ?>
        </div>
        <div class="catalog-product__code">
            Код: <?= $model->backCode ?? null ?>
        </div>
        <div class="catalog-product__cert">
            <div class="cert-trigger">Сертификаты</div>
            <?php if ($model->certificates) { ?>
                <?php if(count($model->certificates) > 1) { ?>
                    <div class="catalog-product__article">Арт: </div>
                    <div class="cert cert_card">
                        <ul class="cert__list">
                            <?php foreach ($model->certificates as $certificate) { ?>
                                <li class="cert__item">
                                    <a href="<?= $certificate->link ?>" download
                                       class="cert__link"><?= $certificate->title ?? 'Сертификат' ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="<?= $model->certificates[0]->link; ?>" class="cert-trigger">
                        <?= $model->certificates[0]->title ?? 'Сертификат' ?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>

    </div>
    <div class="rating rating_sm">
        <div class="rating__fill rating__fill_sm rating__fill_<?= $model->rating ?>"></div>
    </div>
    <div class="catalog-product__price-wrap">
        <?php
        if (Yii::$app->client->isIndividual()) {
            echo $this->render('price/individual', [
                'product' => $model
            ]);
        } elseif (Yii::$app->client->isEntity()) {
            echo $this->render('price/entity', [
                'product' => $model
            ]);
        }
        ?>
    </div>
    <div class="catalog-product__control">
        <div class="regulator regulator_catalog">
            <button class="regulator__button regulator__button_minus _product_count_minus"
                    data-product-id="<?= $model->id ?>">
                -
            </button>
            <input type="text" data-model="balance" value="<?= $model->balance ?>" data-product-id="<?= $model->id ?>"
                   hidden>
            <input min="1" max="99" type="text" class="regulator__input" value="1"
                   data-model="count" data-product-id="<?= $model->id ?>">
            <button class="regulator__button regulator__button_plus _product_count_plus"
                    data-product-id="<?= $model->id ?>">
                +
            </button>
        </div>
        <button class="catalog-product__buy _cart_add"
                data-product-balance="<?= $model->balance ?>"
                data-product-id="<?= $model->id ?>">
            В корзину
        </button>
        <div class="clearfix"></div>
    </div>
    <div class="actions actions_catalog">
        <button class="actions__item actions__item_compare _compare_add"
                data-product-id="<?= $model->id ?>">
            К сравнению
        </button>
        <button class="actions__item actions__item_favorite _favorite_add"
                data-product-id="<?= $model->id ?>">
            В избранное
        </button>
    </div>
    <div class="catalog-product__info-wrap">
        <div class="catalog-product__info catalog-product__info_balance">

            <?php if ($model->balance > 0) { ?>
                <div class="storage-trigger">Остаток</div>
            <?php } else { ?>
                <div class="storage-trigger_style">Остаток</div>
            <?php } ?>

            <div class="storage storage_card">
                <div class="storage__header">
                    <div class="pull-left">
                        Магазины и склады
                    </div>
                    <div class="pull-right">
                        Наличие
                    </div>
                </div>
                <ul class="storage__list">
                    <?php foreach ($model->balances as $balance) { ?>
                        <li class="storage__item">
                            <div class="storage__content">
                                <p class="storage__name"><?= $balance->storage->title ?></p>
                                <span class="storage__address"><?= $balance->storage->address->address ?? null ?></span>
                            </div>
                            <div class="storage__count">
                                <?php if ($balance->inStock) { ?>
                                    <?= ($balance->quantity + 0) . " " . ($model->unit->title ?? null) ?>
                                <?php } else { ?>
                                    <?= $balance->daysToStock ?> дней под заказ
                                <?php } ?>

                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div><?= $model->balance > 0 ? $model->balance : 'Под заказ' ?></div>
        </div>
        <div class="catalog-product__info catalog-product__info_volume">
            <div>Объем</div>
            <div>0.3 м<sup>3</sup></div>
        </div>
        <div class="catalog-product__info catalog-product__info_weight">
            <div>Вес</div>
            <div>220 гр.</div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>