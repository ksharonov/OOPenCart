<?php

/* @var \app\models\db\ProductCategory $category */
/* @var string $searchModelName */
/* @var string $triggerClass */
/* @var string $contentClass */
/* @var app\models\db\ProductFilter $filter */
/* @var \app\models\db\ProductAttribute | \yii\db\ActiveRecord $filterParams */

if ($filter->expanded) {
    $triggerClass = 'filter__trigger filter__trigger_active';
    $contentClass = 'filter__content filter__content_active';
} else {
    $triggerClass = 'filter__trigger';
    $contentClass = 'filter__content';
}
?>

<div class="filter__block" data-filter-group="attribute" data-attribute-id="<?= $filterParams->id ?>">
    <?php
    $arrCounts = $filterParams->testCount($category) ?? null;
    ?>
    <div class="<?= $triggerClass ?>"><?= $filter->attr->title ?></div>
    <div class="<?= $contentClass ?>">
        <div class="filter__wrap">
            <?php foreach ($filterParams->paramsArray as $key => $param) { ?>
                <div class="check">
                    <input id="check-<?= $filter->attr->name ?>-<?= $key ?>"
                           name="<?= $searchModelName ?>[<?= $filter->attr->name ?>][]"
                           value="<?= $param ?>"
                           data-filter-group="checkbox"
                           data-attribute-id="<?= $filterParams->id ?>"
                           type="checkbox" hidden
                           class="check__input">
                    <label class="check__label" for="check-<?= $filter->attr->name ?>-<?= $key ?>">
                        <?= $param ?>
                        <span data-filter-value="<?= $param ?>"
                              data-filter-value-count>
                      <!--  <?php //todo Остаток фильтров ?> (<?php // $arrCounts[$param] ?>) -->
                    </span>
                    </label>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
