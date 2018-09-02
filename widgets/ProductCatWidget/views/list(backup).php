<?php

/* @var array $sidebarOptions */
/* @var \app\models\db\ProductCategory[] $categories */
/* @var \app\models\db\ProductCategory $category */
?>

<div class="<?= $sidebarOptions['class'] ?>">
    <nav>
        <ul class="sidebar__list">

            <?php foreach ($categories as $category) { ?>

                <li class="sidebar__item">
                    <a href="<?= $category->link ?>" class="sidebar__link">
                        <?= $category->title ?>
                    </a>
                    <?php if ($category->hasChild()) { ?>
                        <div class="sidebar__submenu">
                            <ul class="sidebar__sublist">
                                <?php foreach ($category->childs as $childCategory) { ?>
                                    <li class="sidebar__subitem">
                                        <a href="<?= $childCategory->link ?>" class="sidebar__mainlink">
                                            <?= $childCategory->title ?>
                                            <span class="sidebar__count"><?= $childCategory->productsCount ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>