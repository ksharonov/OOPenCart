<?php

namespace app\system\interfaces;

/**
 * Interface IPaymentExtension
 * Описание расширения системы оплаты
 *
 * @package app\system\interfaces
 */
interface IPaymentExtension
{
    /**
     * Действия, которые выполняются до оплаты
     * Метод выполняется до перехода в систему оплаты
     * @param array $params
     * @return mixed
     */
    public static function beforePaid(array $params = []);

    /**
     * Действия, которые выполняются после оплаты
     * Метод выполняется после перехода в систему оплаты/после оплаты
     * @param array $params
     * @return mixed
     */
    public static function afterPaid(array $params = []);
}