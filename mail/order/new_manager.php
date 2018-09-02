<?php

use app\models\db\Setting;
use app\helpers\NumberHelper;

/* @var $order \app\models\db\Order */
$i = 0;
?>

<div id="page_1">
    <br>
    <div class="dclr"></div>
    <p class="p0 ft0">
        Здравствуйте, <?= $order->user->fio ?? $order->user->username ?? $order->user->phone ?? 'Покупатель' ?>!
    </p>
    <p class="p1 ft0">Ваш заказ №<?= $order->id ?> от <?= $order->dtc ?> передан на исполнение.
    </p>
    <p class="p3 ft0">Заказанные товары:</p>
    <table cellpadding="10" cellspacing="0" class="t0" border="1">
        <thead>
        <tr>
            <td class="tr0 td0">№</td>
            <td colspan="5" class="tr0 td3">Наименование</td>
            <td class="tr0 td4">Цена</td>
            <td class="tr0 td5">Количество</td>
            <td class="tr0 td6">Сумма</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order->content as $content) { ?>
            <tr>
                <td class="tr1 td7"><?= ++$i ?></td>
                <td colspan="5" class="tr1 td9">
                    <?= $content->product->title ?>
                </td>
                <td class="tr1 td10"><?= NumberHelper::asMoney($content->priceValue) ?></td>
                <td class="tr1 td11"><?= $content->count ?></td>
                <td class="tr1 td12"><?= NumberHelper::asMoney($content->sum) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if (($order->sum - $order->finalSum) > 0) { ?>
        <p class="p16 ft0">Итого сумма заказа со скидкой: <?= NumberHelper::asMoney($order->finalSum) ?> руб. Сумма
            скидки: <?= NumberHelper::asMoney($order->sum - $order->finalSum) ?> руб.</p>
    <?php } else { ?>
        <p class="p16 ft0">Итого сумма заказа: <?= NumberHelper::asMoney($order->finalSum) ?></p>
    <?php } ?>
    <?php if ($order->deliveryCode) { ?>
        <p class="p17 ft0">Код получения заказа: <?= $order->deliveryCode ?? null ?></p>
    <?php } ?>
    <p class="p17 ft0">Заказ: <?= $order->lastStatus->title ?? null ?>.
        <?php if (!$order->isHasStatus(Setting::get('ORDER.STATUS.PAID'))) { ?>
            Ожидает оплаты до <?= $order->dtReserve ?? null ?>
        <?php } ?>
    </p>
    <p class="p1 ft0">Способ доставки: <?= $order->delivery->extension->title ?? null ?></p>
</div>
