<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 12-12-2017
 * Time: 14:45 PM
 */

namespace app\helpers;

use Yii;
use yii\helpers\Json;

/**
 * Хелпер заказа
 */
class OrderHelper
{
    /**
     * Функция на проверку того, что в массиве содержится заказ
     *
     * @param array $cart
     * @return bool
     */
    public static function isOrder(array $cart)
    {
        foreach ($cart as $element) {
            if (!key_exists('productId', $element) || !key_exists('count', $element)) {
                return false;
            }
            if (!is_int((int)$element['productId']) || count($element) > 2) {
                return false;
            }
        }

        if (count($cart) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Подготовка корзины для заказа
     *
     * @return string | null
     */
    public static function prepare()
    {
        $cookies = Yii::$app->request->cookies;
        $cart = Json::decode($cookies->getValue('cart')) ?? [];

        $cartArray = array_values($cart) ?? [];

        if (self::isOrder($cartArray)) {
            return Json::encode($cartArray);
        } else {
            return null;
        }

    }
}