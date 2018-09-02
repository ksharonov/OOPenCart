<?php
use yii\helpers\Html;

/* @var \app\models\db\Product $model */

$storageBalanceGuestHide = \app\models\db\Setting::get('STORAGE.BALANCE.GUEST.HIDE') && Yii::$app->user->isGuest;
//dump($model->getSqlBalance());
?>

<div class="catalog-product_listview">
    <div class="catalog-product_listview__image-wrap catalog-product_listview__image-wrap_loading">
        <img data-src="/resize/w80<?= $model->mainImage->path ?? '' ?>" class="catalog-product_listview__image">
    </div>
    <div class="catalog-product_listview__content">
        <h3 class="catalog-product_listview__title">
            <a class="catalog-product_listview__link" href="<?= $model->link ?>" data-pjax="0"
               title="<?= $model->title ?>">
                <?= $model->title ?>
            </a>
        </h3>
        <div class="catalog-product_listview__misc-block justify">
            <div class="catalog-product_listview__misc catalog-product_listview__misc_producer justify__item">
                <?= $model->sqlManufacturer ?? "Н/Д" ?>
            </div>
            <div class="catalog-product_listview__misc justify__item">Код: <?= $model->backCode ?></div>
            <div class="catalog-product_listview__misc justify__item">
                <div class="catalog-product__cert">
                    <?php if ($model->certificates) { ?>
                        <?php if (count($model->certificates) > 1) { ?>
                            <div data-dropdown-trigger>Сертификаты</div>
                            <div data-dropdown-content>
                                <ul class="cert__list">
                                    <?php foreach ($model->certificates as $certificate) { ?>
                                        <li class="cert__item">
                                            <a href="<?= $certificate->link ?>" target="_blank" download
                                               class="cert__link">
                                                <?= $certificate->title ?? 'Сертификат' ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a href="<?= $model->certificates[0]->link; ?>" data-pjax="0" class="cert-trigger"
                               target="_blank">
                                <?= $model->certificates[0]->title ?? 'Сертификат' ?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="catalog-product_listview__info-wrap">
            <div class="catalog-product_listview__info">
                <?php if ($model->sqlbalance > 0 && !$storageBalanceGuestHide) { ?>
                    <div class="catalog-product_listview__info-label storage-trigger" data-dropdown-trigger>Остаток
                    </div>
                <?php } else { ?>
                    <div class="catalog-product_listview__info-label storage-trigger_style">Остаток</div>
                <?php } ?>
                <div class="catalog-product_listview__info-value">
                    <?= $model->getSqlBalance() > 0 ? ($storageBalanceGuestHide ? 'В наличии' : $model->getSqlBalance()) : "Под заказ"; ?>
                </div>
                <div class="storage" data-dropdown-content>
                    <div class="storage__header">
                        <div class="pull-left">
                            Магазины и склады
                        </div>
                        <div class="pull-right">
                            Наличие
                        </div>
                    </div>
                    <?php if (!$storageBalanceGuestHide) { ?>
                        <ul class="storage__list">
                            <?php foreach ($model->getSqlBalances(true) as $storage) { ?>
                                <li class="storage__item">
                                    <div class="storage__content">
                                        <p class="storage__name"><?= $storage->title ?></p>
                                        <span class="storage__address"><?= $storage->address->address ?? null ?>
                                            (<?= $storage->city->title ?? null ?>)</span>
                                    </div>
                                    <div class="storage__count">
                                        <?= $storage->_quantity ?> шт.
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            <div class="catalog-product_listview__info">
                <div class="catalog-product_listview__info-label">Объем</div>
                <div class="catalog-product_listview__info-value">
                    <?php if ($model->param->volume) { ?>
                        <?= $model->param->volume ?> м<sup>3</sup>
                    <?php } else { ?>
                        н/д
                    <?php } ?>
                </div>
            </div>
            <div class="catalog-product_listview__info">
                <div class="catalog-product_listview__info-label">Вес</div>
                <div class="catalog-product_listview__info-value">
                    <?php if ($model->param->weight) {
                        if ($model->param->weight >= 1) {
                            echo $model->param->weight; ?> кг.
                        <?php } else {
                            echo $model->param->weight * 1000; ?> гр.
                        <?php } ?>
                    <?php } else { ?>
                        н/д
                    <?php } ?>
                </div>
            </div>
            <?php if ($model->vendorCode) { ?>
                <div class="catalog-product_listview__info">
                    <div class="catalog-product_listview__info-label">Арт.</div>
                    <div class="catalog-product_listview__info-value"><?= $model->vendorCode; ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="catalog-product_listview__meta">
        <!-- НЕ УБИРАТЬ ОБЕРТКУ ФЛАЖКОВ ДАЖЕ ЕСЛИ ФЛАГОВ НЕТ -->
        <div class="flags flags_listview clearfix">
            <?php if ($model->sqlIsSale) { ?>
                <div class="flags__item flags__item_action">Акция</div>
            <?php } ?>
            <?php if ($model->sqlIsBest) { ?>
                <div class="flags__item flags__item_hit">Хит</div>
            <?php } ?>
            <?php if ($model->sqlIsNew) { ?>
                <div class="flags__item flags__item_new">Новинка</div>
            <?php } ?>
        </div>
        <div class="rating rating_sm">
            <div class="rating__fill rating__fill_sm rating__fill_<?= $model->averageRating /*$model->rating*/ ?>"></div>
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
                <input type="text" data-model="balance" value="<?= $model->getSqlBalance() ?>"
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
