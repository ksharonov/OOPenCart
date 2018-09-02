<?php
/* @var \app\models\db\Manufacturer[] $brands */
?>

<div>
    <h2 class="_220v__section-title col-xs-30">Партнеры</h2>
    <div class="col-xs-18">
        <a href="/brands" class="_220v__index-link">Все партнеры</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="index-partners">
    <div class="col-lg-48">
        <div class="index-partners__container">
            <div class="index-partners__slider">
                <?php foreach ($brands as $brand)  { ?>
                    <?php if(isset($brand->file->path) && isset($brand->slug)) { ?>
                        <a href="/brands/<?= $brand->slug; ?>" class="index-partners__slide">
                            <img src="<?= $brand->file->path; ?>">
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>