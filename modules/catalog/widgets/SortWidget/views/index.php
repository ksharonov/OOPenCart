<?php

/* @var array $sortData */
?>

<?php if (false) { ?>
    <div class="sort">
        <?php foreach ($sortData as $item) { ?>
            <a href="#sort" data-sort="-" data-sort-col="<?= $item->sort ?>"
               data-link="sort=<?= $item->sort ?>" class="sort__link sort__link_price sort__link_up _sort_submit"
               data-pjax="1"><?= $item->title ?></a>
        <?php } ?>
        <!--    <a href="#" class="sort__link sort__link_price sort__link_up">Сортировать по цене</a>-->
        <!--    <a href="#" class="sort__link sort__link_rating sort__link_down">Сортировать по цене</a>-->
        <!--    <a href="#" class="sort__link sort__link_sale">Сортировать по цене</a>-->
    </div>
<?php } ?>
<div class="sort">
    Сортировать:
    <select class="sort__select">
        <?php foreach ($sortData as $item) { ?>
            <option value="<?= $item['value'] ?>"><?= $item['title'] ?></option>
        <?php } ?>
    </select>
</div>