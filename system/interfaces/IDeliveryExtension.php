<?php

namespace app\system\interfaces;

/**
 * Interface IDeliveryExtension
 * Описание расширения системы доставки
 *
 * @package app\system\interfaces
 */
interface IDeliveryExtension
{
    /**
     * Действия которые выполняются после установки данных по доставке
     * @param array $params
     * @return mixed
     */
    public static function afterSet(array $params = []);

    /**
     * Расчёт цены доставки данного метода доставки
     * @param array $params
     * @return mixed
     */
    public static function calcCostDelivery(array $params = []);
}