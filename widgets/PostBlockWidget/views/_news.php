<?php
/* @var \app\models\db\Post[] $model */
/* @var \app\models\db\Post $element */
?>

<div>
    <h2 class="_220v__section-title col-xs-30">Новости</h2>
    <div class="col-xs-18">
        <a href="/news" class="_220v__index-link">Все новости</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="news-index">
    <div class="news-index__list">
        <?php foreach ($model as $element) { ?>
            <div class="col-lg-16 col-md-16 col-sm-16">
                <div class="news-index__item">
                    <time class="news-index__date"><?= $element->dt ?></time>
                    <h3 class="news-index__title">
                        <a href="<?= $element->link ?>" data-pjax="0" class="news-index__link">
                            <?= $element->title ?>
                        </a>
                    </h3>
                </div>
            </div>
        <? } ?>
    </div>
</div>