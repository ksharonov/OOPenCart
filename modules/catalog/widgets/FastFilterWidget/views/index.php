<?php
/* @var array $fastFilters */
?>

<div class="sort_fast">
    <div class="col-lg-48">
        <?php foreach ($fastFilters as $title => $fastFilter) { ?>
            <a href="#" data-link='<?= $fastFilter ?>' class="sort__fast _fast_filter_submit"><?= $title ?></a>
        <?php } ?>
    </div>
</div>
