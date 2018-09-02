<?php
use yii\helpers\Html;
//return;
/* @var \app\models\db\Product $model */

?>

<div class="catalog-product_listview">
    <div class="catalog-product_listview__image-wrap catalog-product_listview__image-wrap">
        <img src="<?= $model->mainImage->path ?? '' ?>" class="catalog-product_listview__image">
    </div>
    <div class="catalog-product_listview__content">
        <h3 class="catalog-product_listview__title">
            <a class="catalog-product_listview__link" href="<?= $model->link ?>" data-pjax="0"
               title="<?= $model->title ?>">
                <?= $model->title ?>
            </a>
        </h3>
        <div class="catalog-product_listview__misc-block justify">
            <div class="catalog-product_listview__misc justify__item"><?= $model->manufacturer->title ?? "Н/Д" ?></div>
            <div class="catalog-product_listview__misc justify__item">Код: <?= $model->backCode ?></div>
            <div class="catalog-product_listview__misc justify__item">
                <div class="catalog-product__cert">
                    <?php if ($model->certificates) { ?>
                        <?php if(count($model->certificates) > 1) { ?>
                            <div class="cert-trigger">Сертификаты</div>
                            <div class="cert cert_card">
                                <ul class="cert__list">
                                    <?php foreach ($model->certificates as $certificate) { ?>
                                        <li class="cert__item">
                                            <a href="<?= $certificate->link ?>" download class="cert__link">
                                                <?= $certificate->title ?? 'Сертификат' ?>
                                            </a>
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
        </div>
        <div class="catalog-product_listview__info-wrap">
            <div class="catalog-product_listview__info">

                <?php if ($model->balance > 0) { ?>
                    <div class="catalog-product_listview__info-label storage-trigger">Остаток</div>
                <?php } else { ?>
                    <div class="catalog-product_listview__info-label storage-trigger_style">Остаток</div>
                <?php } ?>

                <div class="storage">
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
                                        <?= $balance->quantity ?> шт.
                                    <?php } else { ?>
                                        <?= $balance->daysToStock ?> дней под заказ
                                    <?php } ?>

                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="catalog-product_listview__info-value"><?= $model->balance > 0 ? $model->balance : 'Под заказ' ?></div>
            </div>
            <div class="catalog-product_listview__info">
                <div class="catalog-product_listview__info-label">Объем</div>
                <div class="catalog-product_listview__info-value">0.3 м<sup>3</sup></div>
            </div>
            <div class="catalog-product_listview__info">
                <div class="catalog-product_listview__info-label">Вес</div>
                <div class="catalog-product_listview__info-value">220 гр.</div>
            </div>
        </div>
    </div>
    <div class="catalog-product_listview__meta">
        <!-- НЕ УБИРАТЬ ОБЕРТКУ ФЛАЖКОВ ДАЖЕ ЕСЛИ ФЛАГОВ НЕТ -->
        <div class="flags flags_listview clearfix">
            <?php if ($model->isDiscount) { ?>
                <div class="flags__item flags__item_action">Акция</div>
            <?php } ?>
            <?php if ($model->isBest) { ?>
                <div class="flags__item flags__item_action">Хит</div>
            <?php } ?>
            <?php if ($model->getIsNew()) { ?>
                <div class="flags__item flags__item_action">Новинка</div>
            <?php } ?>
        </div>
        <div class="rating rating_sm">
            <div class="rating__fill rating__fill_sm rating__fill_<?= $model->rating ?>"></div>
        </div>
    </div>
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
    <div class="catalog-product_listview__operation">
        <div class="catalog-product_listview__control">
            <div class="regulator regulator_catalog clearfix">
                <button class="regulator__button regulator__button_minus _product_count_minus"
                        data-product-id="<?= $model->id ?>">
                    -
                </button>
                <input type="text" data-model="balance" value="<?= $model->balance ?>"
                       data-product-id="<?= $model->id ?>" hidden>
                <input min="1" max="99" type="text" class="regulator__input" value="1" data-model="count"
                       data-product-id="<?= $model->id ?>">
                <button class="regulator__button regulator__button_plus _product_count_plus"
                        data-product-id="<?= $model->id ?>">
                    +
                </button>
            </div>
            <button class="catalog-product__buy _cart_add"
                    data-product-id="<?= $model->id ?>">В корзину
            </button>
            <div class="clearfix"></div>
        </div>
        <div class="actions actions_catalog">
            <button class="actions__item actions__item_compare _compare_add" data-product-id="<?= $model->id ?>">
                К сравнению
            </button>
            <button class="actions__item actions__item_favorite _favorite_add" data-product-id="<?= $model->id ?>">
                В избранное
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
