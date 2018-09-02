<?php

/* @var array $sidebarOptions */
/* @var \app\models\db\ProductCategory[] $categories */
/* @var \app\models\db\ProductCategory $category */
?>

<div class="<?= $sidebarOptions['class'] ?>">
    <nav>
        <ul class="sidebar__list">

            <?php foreach ($categories as $category) { ?>
                <?php if ($category->parentId !== null) {
                    continue;
                } ?>
                <li class="sidebar__item">
                    <a href="<?= $category->link ?>" class="sidebar__link">
                        <?= $category->title ?>
                    </a>
                    <?php if ($category->sqlChilds) { ?>
                        <div class="sidebar__submenu">
                            <ul class="sidebar__sublist">
                                <?php foreach ($category->sqlChilds as $index) { ?>
                                    <?php if (!isset($categories[$index])) {
                                        continue;
                                    } ?>
                                    <li class="sidebar__subitem">
                                        <a href="<?= $categories[$index]->link ?>" class="sidebar__mainlink">
                                            <?= $categories[$index]->title ?>
                                            <span class="sidebar__count"><?= $categories[$index]->sqlTotalCount ?></span>
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