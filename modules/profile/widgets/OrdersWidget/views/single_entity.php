<?php

use app\helpers\NumberHelper;

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\db\Order;
use yii\helpers\ArrayHelper;

/* @var \app\models\search\OrderSearchProfile $searchModel */
/* @var Order $order */
/* @var \app\models\db\Extension[] $payments */
$display = !is_null($order) ? 'block' : 'none';
//dump($order->lastStatus);
?>
<div class="popup" id="orderMore" data-modal style="display:<?= $display ?>;">
    <?php if ($order) { ?>
        <div class="popup__cover">
            <div class="popup__block popup__block_order">
                <a href="?id=null&OrderSearchProfile[search]=<?= $searchModel->search ?>"
                   class="popup__close _order_close"
                   data-prevent="1" data-m-target="#orderMore"
                   data-m-dismiss="modal">Закрыть</a>
                <div class="order">
                    <div class="order__block">
                        <span class="order__state"><?= $order->lastStatus->title ?></span>
                        <h4 class="order__title">
                            Заказ №<?= $order->id ?> от <?= $order->dtc ?>
                            <button class="order__caller">Документы</button>
                        </h4>
                    </div>
                    <div class="order__block">
                        <div class="order__wrap">
                            <table class="order__list">
                                <thead>
                                <tr>
                                    <th class="order__head order__head_padding">Наименование товара</th>
                                    <th class="order__head order__head_right order__head_padding">Цена</th>
                                    <th class="order__head order__head_right order__head_padding">Кол.</th>
                                    <th class="order__head order__head_right">Стоимость</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($order->content as $content) { ?>
                                    <tr>
                                        <td class="order__property order__property_padding">
                                            <a href="<?= $content->product->link ?>" data-pjax="0"
                                               class="order__link"><?= $content->title ?></a>
                                        </td>
                                        <td class="order__property order__property_right order__property_padding"><?= NumberHelper::asMoney($content->priceValue) ?></td>
                                        <td class="order__property order__property_right order__property_padding"><?= (float)$content->count ?></td>
                                        <td class="order__property order__property_right"><?= NumberHelper::asMoney($content->priceValue * (float)$content->count) ?>
                                            <span class="rouble">₽</span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="order__block order__block_bottom">
                        <div class="row">
                            <div class="col-md-48">
                                <div class="order__controls">
                                    <div class="order__priceblock">
                                        <div class="order__label order__label_sum">Сумма без НДС</div>
                                        <div class="order__value order__value_sum">
                                            <?= NumberHelper::asMoney($order->sumWVat) ?> <span class="rouble">₽</span>
                                        </div>
                                    </div>
                                    <div class="order__priceblock">
                                        <div class="order__label order__label_sum">НДС</div>
                                        <div class="order__value order__value_sum">
                                            <?= NumberHelper::asMoney($order->vat) ?> <span class="rouble">₽</span>
                                        </div>
                                    </div>
                                    <div class="order__priceblock">
                                        <div class="order__label order__label_sum">Итого</div>
                                        <div class="order__value order__value_sum">
                                            <?= NumberHelper::asMoney($order->sum) ?> <span class="rouble">₽</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-48">
                                <div class="order__info">
                                    <div class="order__label">Способ оплаты</div>
                                    <?= Html::dropDownList(null, $order->paymentMethod, ArrayHelper::map($payments, 'id', 'title'), [
                                        'class' => 'order__select',
                                        'disabled' => true
                                    ]); ?>
                                </div>
                                <div class="order__info">
                                    <div class="order__label">Способ получения</div>
                                    <div class="order__value">
                                        <?= $order->delivery->extension->title ?> <?= $order->delivery->extension->model->getText($order->deliveryData) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-48">
                                <?php
                                if (!$order->isHasStatus(\app\models\db\Setting::get('ORDER.STATUS.PAID'))
                                ) { ?>
                                    <a href="/order/submit/pay?id=<?= $order->id ?>" class="order__submit">Оплатить</a>
                                <?php } ?>
                                <!--                                <a class="order__submit">Сформировать счет</a>-->
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="order__documents">
                        <h5 class="order__subtitle">
                            <?php if ($order->documents) { ?>
                                Сопроводительные документы
                            <?php } else { ?>
                                Документы отсутствуют
                            <?php } ?>
                            <button class="order__documents-close"></button>
                        </h5>
                        <ul class="order__documents-list">
                            <?php foreach ($order->documents as $document) { ?>
                                <li class="order__document">
                                    <a href="<?= $document->link ?>"
                                       data-pjax="0"
                                       class="order__link"><?= $document->title ?? 'Документ' ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>