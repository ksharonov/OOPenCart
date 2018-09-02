<?php
use yii\widgets\LinkPager;

/* @var \yii\data\Pagination|false $pagination */
/* @var string $currentUrl */

?>

<div class="pagination clearfix">
    <div class="col-lg-48">
        <a class="pagination__more"
           href="<?= $currentUrl ?>">Показать
            больше</a>

        <?php if (isset($showAll)) :?>
        <a class="pagination__more _pagination_all"
           href="<?= $showAll ?>" data-pjax="0">Показать
            все</a>
        <?php endif; ?>
    </div>
    <div class="col-lg-48">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'prevPageLabel' => 'Назад',
            'nextPageLabel' => 'Вперёд',
            'options' => [
                'class' => 'pagination__list',
            ],
            'linkOptions' => [
                'class' => 'pagination__link',
                'data-pjax' => 1
            ],
            'linkContainerOptions' => [
                'class' => 'pagination__item'
            ],
            'disabledListItemSubTagOptions' => [
                'class' => 'pagination__link'
            ],
            'activePageCssClass' => 'pagination__item_active',
            'maxButtonCount' => 5
        ]);
        ?>
    </div>
</div>