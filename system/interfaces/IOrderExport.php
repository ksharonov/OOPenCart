<?php

namespace app\system\interfaces;

/**
 * Interface IOrderExport
 * Интерфейс отправки заказа в БэкОфис (напр: 1С, Лексема и другие)
 *
 * @package app\system\interfaces
 */
interface IOrderExport
{
    /**
     * Подготовка к возможности отправки заказа
     * @param \app\models\db\Order $order
     * @return bool
     */
    public function prepareOrder(\app\models\db\Order &$order): bool;

    /**
     * Отправка заказа
     * @param \app\models\db\Order $order
     * @return bool
     */
    public function sendOrder(\app\models\db\Order &$order): bool;
}