<?php

use app\helpers\NumberHelper;

/* @var \app\models\base\Cart $cart */
/* @var \app\models\base\order\OrderProcess $process */
/* @var \app\models\session\OrderSession $order */
?>

<div class="summary">
    <div class="summary__sum"><?= NumberHelper::asMoney($order->order->finalSum ?? null) ?? NumberHelper::asMoney($order->finalSum) ?? $cart->sum ?> <span class="rouble">₽</span>
    </div>
    <?php if ($process->additions) { ?>
        <div class="summary__address">
            <h4 class="summary__title">Дополнительные затраты</h4>
            <p class="summary__text">
                <?php
                foreach ($process->additions as $title => $cost) {
                    echo "{$title}: {$cost} <span class=\"rouble\">₽</span><br>";
                }
                ?>
            </p>
        </div>
    <?php } ?>
    <?php if ($process->discounts) { ?>
        <div class="summary__address">
            <h4 class="summary__title">Скидки</h4>
            <p class="summary__text">
                <?php
                $fullCost = 0;
                foreach ($process->discounts as $title => $cost) {
                    $fullCost += $cost;
                    echo "{$title}: {$cost} <span class=\"rouble\">₽</span><br>";
                }

                if ($order->finalSum < $cart->sum) {
                    echo "Скидки на товары: " . NumberHelper::asMoney($cart->sum - $order->finalSum - $fullCost) . " <span class=\"rouble\">₽</span><br>";
                }
                ?>
            </p>

        </div>
    <?php } ?>
    <?php if ($process->delivery) { ?>
        <div class="summary__address">
            <h4 class="summary__title">Способ получения</h4>
            <p class="summary__text"><?= $process->deliveryText ?></p>
        </div>
    <?php } ?>
    <?php if ($process->contacts) { ?>
        <div class="summary__info">
            <?php if (false) { ?>
                <!--
                todo поправить это гавно
                 -->
            <?php } ?>
            <h4 class="summary__title">Контактная информация</h4>
            <p class="summary__text">Имя: <?= $process->contacts->name ?? '' ?></p>
            <p class="summary__text">Тел: <?= $process->contacts->phone ?? '' ?></p>
            <p class="summary__text">Эл.почта: <?= $process->contacts->email ?? '' ?></p>
            <?php if (isset($process->contacts->inn)) { ?>
                <p class="summary__text">ИНН: <?= $process->contacts->inn ?? '' ?></p>
                <p class="summary__text">КПП: <?= $process->contacts->kpp ?? '' ?></p>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if ($process->payment) { ?>
        <div class="summary__info">
            <h4 class="summary__title">Способ оплаты</h4>
            <p class="summary__text"><?= $process->payment->title ?></p>
        </div>
    <?php } ?>
    <div class="summary__warning">Указанное предложение действительно на <?= date('d.m.Y'); ?></div>
</div>
