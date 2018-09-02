<?php
use app\models\db\Product;

/* @var Product $model */
/* @var array $data */
?>
<div class="attributes">
    <h2 class="attributes__title">Описание</h2>
    <div class="attributes__text" itemprop="description">
        <?= $model->content ?>
    </div>
</div>
<br>

<?php foreach ($data as $titleGroup => $attrs) { ?>
    <div class="attributes">
        <h2 class="attributes__title"><?= $titleGroup ?? "-" ?></h2>
        <div class="attributes__text attributes__text_dots">
            <?php foreach ($attrs as $title => $attr) { ?>
                <p class="attribitutes__char">
                        <span class="attributes__label">
                            <?= $title ?? '-' ?>
                            <span class="attributes__dots"></span>
                        </span>
                    <span><?= $attr->attrValue ?? '-' ?></span>
                </p>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<table class="table table-condensed" style="display: none;">
    <tbody>
    <?php foreach ($data as $titleGroup => $attrs) { ?>
        <tr>
            <th colspan="2"><?= $titleGroup ?? "-" ?></th>
        </tr>
        <?php foreach ($attrs as $title => $attr) { ?>
            <tr>
                <td><?= $title ?? "-" ?></td>
                <td><?= $attr->attrValue ?? "-" ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>