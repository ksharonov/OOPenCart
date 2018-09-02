<?php

/* @var yii\web\View $this */
/* @var array $tabs */
/* @var object $options */

$isStepMode = isset($options->step) && $options->step;
?>

<div class="tabs" data-tab-name="<?= $id ?>">
    <div class="tabs__list" data-tab-element="list">
        <?php foreach ($tabs as $tab) { ?>
            <a class="tabs__item tabs__item_active <?= $tab->listTabOptions->class ?? null ?>"
               data-tab-main="<?= $id ?>" data-tab-item="<?= $tab->id ?>"
               data-tab-element="list-item"
                <?= $isStepMode ? 'data-step' : '' ?>
               href="#<?= $tab->id ?>">
                <?= $tab->title ?>
            </a>
        <?php } ?>
    </div>
    <div class="tabs__wrap" data-tab-element="content">
        <?php foreach ($tabs as $tab) { ?>
            <?php
            $viewPath = $tab->viewPath ?? $viewPath;
            ?>
            <a href="#" class="tabs__trigger"
               data-tab-main="<?= $id ?>"
               data-tab-item="<?= $tab->id ?>"
               data-tab-element="trigger-item"><?= $tab->title ?></a>
            <div id="desc" class="tabs__panel tabs__panel_active  <?= $tab->listContentOptions->class ?? null ?>"
                 data-tab-main="<?= $id ?>"
                 data-tab-item="<?= $tab->id ?>"
                 style="display:none;"
                 data-tab-element="content-item">
                <?php if (key_exists('view', $tab)) {
                    echo $this->render($viewPath . '/' . $tab->view, $tab->params);
                } elseif (key_exists('widget', $tab)) {
                    echo $tab->widget::widget($tab->params);
                } else {
                    dump($tab);
                }
                ?>
            </div>
        <?php } ?>
    </div>

    <?php
    if ($isStepMode) {
        echo $this->render('_steps', [
            'id' => $id
        ]);
    }
    ?>

</div>