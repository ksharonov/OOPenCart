<?php
/* @var \app\models\db\Post[] $model */
/* @var \app\models\db\Post $element */
/* @var string $title */
?>

<div>
    <h2 class="_220v__section-title col-xs-30"><?= $title ?></h2>
    <div class="col-xs-18">
        <a href="/reviews" class="_220v__index-link">Все <?= $title?></a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="reviews-index">
    <div class="reviews-index__list">
        <?php foreach ($model as $element) { ?>
            <div class="col-lg-16 col-md-16 col-sm-16">
                <div class="reviews-index__item">
                    <div class="reviews-index__image-wrap">
                        <img class="reviews-index__image" src="<?= $element->mainImage->path ?>"
                             alt="<?= $element->title; ?>">
                    </div>
                    <time class="reviews-index__date"><?= $element->dt ?></time>
                    <h3 class="reviews-index__title">
                        <a href="<?= $element->link ?>" data-pjax="0" class="reviews-index__link">
                            <?= $element->title ?>
                        </a>
                    </h3>
<!--                    <p class="reviews-index__text">-->
<!--                        --><?//= $element->shortContent ?>
<!--                    </p>-->
                </div>
            </div>
        <? } ?>
    </div>
</div>