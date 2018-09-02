<?php

use app\models\db\Setting;
use app\helpers\NumberHelper;

/* @var $order \app\models\db\Order */
$i = 0;
?>

<div id="page_1">
    <div id="p1dimg1">
        <img src="http://220volt-ufa.ru/img/header__logo.png" alt="">
        <!--        <img src="data:image/jpg;base64,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">-->
    </div>
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
        <p class="p16 ft0">Итого сумма заказа со скидкой: <?= NumberHelper::asMoney($order->finalSum) ?> руб. Сумма скидки: <?= NumberHelper::asMoney($order->sum - $order->finalSum) ?> руб.</p>
    <?php } else { ?>
        <p class="p16 ft0">Итого сумма заказа: <?= NumberHelper::asMoney($order->finalSum) ?></p>
    <?php } ?>
    <?php if ($order->deliveryCode) { ?>
        <p class="p17 ft0">Код получения заказа: <?= $order->deliveryCode ?? null ?></p>
    <?php } ?>
    <p class="p17 ft0">Заказ: <?= $order->lastStatus->title ?? null ?>,
        <?php if (!$order->isHasStatus(Setting::get('ORDER.STATUS.PAID'))) { ?>
            зарезервирован до  <?= $order->dtReserve ?? null ?>
        <?php } ?>
    </p>
    <p class="p1 ft0">Способ доставки: <?= $order->delivery->extension->title ?? null ?></p>
    <p class="p17 ft8">Компания <?= \app\models\db\Setting::get('SITE.NAME') ?></p>
    <p class="p18 ft10">
        <nobr>+7(34783)700-17</nobr>
        <span class="ft9">(многоканальный)</span></p>
    <p class="p18 ft9">
        <span class="ft10">+7 917-046-26-19</span>
        <span class="ft10">(по всем вопросам интернет-магазина)</span>
    </p>
    <p class="p19 ft5">Данное письмо сформировано автоматически, просьба не отвечать на него</p>
    <p class="p20 ft11">
        <a href="http://220volt-ufa.ru/"><?= Setting::get('SITE.URL') ?></a><br>
        <a href="https://vk.com/volt220ufa">https://vk.com/volt220ufa</a><br>
        <a href="http://www.instagram.com/kompaniya220volt">www.instagram.com/kompaniya220volt</a><br>
    </p>
</div>
