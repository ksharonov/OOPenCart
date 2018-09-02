<?php
use app\modules\catalog\widgets\FilterWidget\FilterWidget;

/* @var string $searchModelName */
/* @var app\models\db\ProductFilter[] $filters */
/* @var app\models\db\ProductFilter $filter */
/* @var \app\models\db\ProductCategory $category */
/* @var \app\models\db\ProductCategory[] $categories */
/* @var \yii\base\Widget $widgetName */
/* @var \app\models\db\Manufacturer $manufacturer */
/* @var \app\models\db\Manufacturer[] $manufacturers */
/* @var \app\models\db\CityOnSite[] $cities */
/* @var $search bool */
/* @var $this \yii\web\View */
?>

<!--noindex-->
<form class="filter">
    <div class="filter__block" hidden>
        <?php if ($category) { ?>
            <input type="text" value="<?= $category->id ?? null ?>"
                   name="<?= $searchModelName ?>[category]" hidden>
        <?php } ?>

        <?php if ($search) { ?>
            <input type="text" value="<?= $search ?? null ?>"
                   id="search-value"
                   name="<?= $searchModelName ?>[search]" hidden>
        <?php } ?>

        <?php if ($manufacturer) { ?>
            <input type="text" value="<?= $manufacturer->id ?? null ?>"
                   name="<?= $searchModelName ?>[manufacturerFilter]" hidden>
        <?php } ?>
    </div>
    <div class="filter__block">
        <div class="filter__trigger filter__trigger_active">Цена, Р</div>
        <div class="filter__content filter__content_active">
            <div class="price-range">
                <div class="clearfix">
                    <input type="text" name="ProductSearch[priceFilter][]" value="0"
                           class="price-range__input price-range__input_min">
                    <input type="text" name="ProductSearch[priceFilter][]" value="0"
                           class="price-range__input price-range__input_max">
                </div>
                <div class="clearfix">
                    <input type="text" class="price-range__range" hidden>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$category) { ?>
        <div class="filter__block">
            <div class="filter__trigger filter__trigger_active">Категория</div>
            <div class="filter__content filter__content_active">
                <div class="filter__wrap">
                    <?php foreach ($categories as $category) { ?>
                        <div class="check">
                            <input id="category-<?= $category->id ?>" name="ProductSearch[category][]"
                                   type="checkbox" hidden
                                   value="<?= $category->id ?>"
                                   class="check__input">
                            <label class="check__label" for="category-<?= $category->id ?>">
                                <?= $category->title ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
    //todo возможно позднее вынести отдельно
    ?>
    <?php if (Yii::$app->client->isEntity()) { ?>
        <div class="filter__block">
            <div class="filter__trigger filter__trigger_active">Только в наличии</div>
            <div class="filter__content filter__content_active">
                <div class="filter__wrap">
                    <div class="check">
                        <input id="check-balance-1" name="ProductSearch[balanceFilter]"
                               type="checkbox" hidden
                               value="1"
                               class="check__input">
                        <label class="check__label" for="check-balance-1">
                            Да
                        </label>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if (\app\models\db\Setting::get('WIDGET.CITY.SELECT.SHOW')) { ?>
        <div class="filter__block">
            <div class="filter__trigger filter__trigger_active">Остатки по городам</div>
            <div class="filter__content filter__content_active">
                <div class="filter__wrap">
                    <div class="check">
                        <input id="check-city-all" name="ProductSearch[cityFilter]"
                               value="0"
                               type="checkbox" hidden
                               class="check__input">
                        <label class="check__label" for="check-city-all">
                            Все города
                        </label>
                    </div>
                    <?php foreach ($cities as $city) { ?>
                        <div class="check">
                            <input id="check-city-<?= $city->cityId ?>" name="ProductSearch[cityFilter][]"
                                   value="<?= $city->cityId ?>"
                                   type="checkbox" hidden
                                   class="check__input">
                            <label class="check__label" for="check-city-<?= $city->cityId ?>">
                                <?= $city->city->title ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if (count($manufacturers) > 0) { ?>
        <div class="filter__block">
            <div class="filter__trigger filter__trigger_active">Производитель</div>
            <div class="filter__content filter__content_active">
                <div class="filter__wrap">
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                        <div class="check">
                            <input id="check-manufacturer-<?= $manufacturer->id ?>"
                                   name="ProductSearch[manufacturerFilter][]"
                                   value="<?= $manufacturer->id ?>"
                                   type="checkbox" hidden
                                   class="check__input">
                            <label class="check__label" for="check-manufacturer-<?= $manufacturer->id ?>">
                                <?= $manufacturer->title ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php foreach ($filters as $filter) {

        $widgetName = FilterWidget::$widgetByType[$filter->type];

        echo $widgetName::widget([
            'category' => $category,
            'searchModelName' => $searchModelName,
            'filter' => $filter,
            'filterParams' => $filter->filterParams
        ]);
    }
    ?>

    <div class="filter__block">
        <button type="button" class="catalog-product__buy _filter_submit">
            Показать
        </button>
        <br><br>
    </div>
</form>
<!--/noindex-->