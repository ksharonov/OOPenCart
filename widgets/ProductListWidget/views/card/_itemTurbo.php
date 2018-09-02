<?php
use yii\helpers\Html;

//return;
/* @var \app\models\db\Product $model */
$storageBalanceGuestHide = \app\models\db\Setting::get('STORAGE.BALANCE.GUEST.HIDE') && Yii::$app->user->isGuest;
?>
<div class="catalog-product">
    <div class="catalog-product__image-wrap catalog-product__image-wrap_loading">
        <img data-src="/resize/w600<?= $model->mainImage->path ?? '' ?>" class="catalog-product__image">
    </div>
    <div class="catalog-product__flags">
        <div class="flags">
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
    </div>
    <h3 class="catalog-product__title">
        <a href="<?= $model->link ?>" class="catalog-product__link" data-pjax="0" title="<?= $model->title ?>">
            <?= $model->title ?>
        </a>
    </h3>
    <div class="catalog-product__misc">
        <div class="catalog-product__producer" title="<?= $model->sqlManufacturer ?? "Н/Д" ?>">
            <?= $model->sqlManufacturer ?? "Н/Д" ?>
        </div>
        <div class="catalog-product__code">
            Код: <?= $model->backCode ?? null ?>
        </div>
        <div class="catalog-product__cert">
            <?php if($model->vendorCode) { ?>
                <div class="catalog-product__article">Арт: <?= $model->vendorCode; ?></div>
            <?php } ?>
            <?php if ($model->certificates) { ?>
                <?php if (count($model->certificates) > 1) { ?>
                    <div data-dropdown-trigger>Сертификаты</div>
                    <div data-dropdown-content>
                        <ul class="cert__list">
                            <?php foreach ($model->certificates as $certificate) { ?>
                                <li class="cert__item">
                                    <a href="<?= $certificate->link ?>" target="_blank" download
                                       class="cert__link"><?= $certificate->title ?? 'Сертификат' ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="<?= $model->certificates[0]->link; ?>" data-pjax="0" class="cert-trigger" target="_blank">
                        <?= $model->certificates[0]->title ?? 'Сертификат' ?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="rating rating_sm rating_catalog">
        <div class="rating__fill rating__fill_sm rating__fill_<?= $model->averageRating /*$model->rating*/ ?>"></div>
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
            <input type="text" data-model="balance" value="<?= $model->getSqlBalance() ?>"
                   data-product-id="<?= $model->id ?>"
                   hidden>
            <input min="1" max="99" type="text" class="regulator__input" value="1"
                   data-model="count" data-product-id="<?= $model->id ?>">
            <button class="regulator__button regulator__button_plus _product_count_plus"
                    data-product-id="<?= $model->id ?>">
                +
            </button>
        </div>
        <button class="catalog-product__buy _cart_add"
                data-product-balance="<?= $model->getSqlBalance() ?>"
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
            <?php if ($model->getSqlBalance() > 0 && !$storageBalanceGuestHide) { ?>
                <div class="storage-trigger" data-dropdown-trigger>Остаток</div>
            <?php } else { ?>
                <div class="storage-trigger_style">Остаток</div>
            <?php } ?>

            <div class="storage storage_card" data-dropdown-content>
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
                        <?php foreach ($model->sqlBalances as $storage) { ?>
                            <li class="storage__item">
                                <div class="storage__content">
                                    <p class="storage__name"><?= $storage->title ?></p>
                                    <span class="storage__address"><?= $storage->address->address ?? null ?>(<?= $storage->city->title ?? null ?>)</span>
                                </div>
                                <div class="storage__count">

                                    <?= ($storage->_quantity + 0) . " " . ($model->unit->title ?? null) ?>


                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div><?= $model->getSqlBalance() > 0 ? ($storageBalanceGuestHide ? 'В наличии' : $model->getSqlBalance()) : "Под заказ"; ?></div>
        </div>
        <div class="catalog-product__info catalog-product__info_volume">
            <div>Объем</div>
            <div>
				<?php if ($model->param->volume) { ?>
					<?= $model->param->volume ?> м<sup>3</sup>
				<?php } else { ?>
                    н/д
				<?php } ?>
            </div>
        </div>
        <div class="catalog-product__info catalog-product__info_weight">
            <div>Вес</div>
            <div>
				<?php if ($model->param->weight) {
				    if ($model->param->weight >= 1) {
				        echo $model->param->weight; ?> кг.
                    <?php } else {
					    echo $model->param->weight * 1000;?> гр.
                    <?php } ?>
				<?php } else { ?>
                    н/д
				<?php } ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>