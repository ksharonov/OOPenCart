<?php

namespace app\helpers;

use NumberFormatter;

class NumberHelper
{
    /** Приведение числа с денежному виду
     * @param      $value
     * @param bool $isFloat вывод знаков после запятой(копейки итд)
     * @return null|string
     */
    public static function asMoney($value, $isFloat = true)
    {
        if (is_null($value)) {
            return null;
        }
        //todo подумать про отсутствие поддержки INTL
        $fmt = new NumberFormatter('ru_RU', NumberFormatter::CURRENCY);

        //todo Добавить параметр в базу?
        if ($value > 100000) {
            $isFloat = false;
        }

        if ($isFloat) {
            $fmt->setPattern('#,##0.00'); // #,##0.00 ¤
        } else {
            $fmt->setPattern('#,##0');
        }

        $value = $fmt->formatCurrency($value, 'RUR');

        return $value ?? null;
    }
}