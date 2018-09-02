<div>
    <ul class="breadcrumbs" itemscope="http://schema.org/BreadcrumbList">
        <?php foreach ($crumbs as $crumb) { ?>

            <li class="breadcrumbs__item" itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a href="<?= $crumb->link ?>" class="breadcrumbs__link" itemprop="item"><?= $crumb->title ?></a>
            </li>

        <?php } ?>
    </ul>
</div>