<?php

namespace app\helpers;

use app\models\db\Product;
use app\models\db\Post;
use app\models\db\Page;
use app\models\db\User;
use app\models\db\Client;
use yii\db\ActiveRecord;

/**
 * Хелпер связанных моделей
 *
 * @static array $relModels
 */
class ModelRelationHelper
{
    const REL_MODEL_PRODUCT = 0;
    const REL_MODEL_POST = 1;
    const REL_MODEL_PAGE = 2;
    const REL_MODEL_USER = 3;
    const REL_MODEL_CLIENT = 4;
    const REL_MODEL_MANUFACTURER = 5;
    const REL_MODEL_ORDER = 6;
    const REL_MODEL_STORAGE = 7;
    const REL_MODEL_PRODUCT_PRICE_GROUP = 8;
    const REL_MODEL_PRODUCT_ATTRIBUTE = 9;
    const REL_MODEL_UNIT = 10;
    const REL_MODEL_PRODUCT_CATEGORY = 11;
    const REL_MODEL_PRODUCT_PRICE = 12;
    const REL_MODEL_PRODUCT_ANALOGUE = 13;
    const REL_MODEL_CONTRACT = 14;
    const REL_MODEL_DISCOUNT = 15;
    const REL_MODEL_CART = 16;

    public static $model = [
        'Product' => self::REL_MODEL_PRODUCT,
        'Post' => self::REL_MODEL_POST,
        'Review' => self::REL_MODEL_POST,
        'News' => self::REL_MODEL_POST,
        'Page' => self::REL_MODEL_PAGE,
        'User' => self::REL_MODEL_USER,
        'Client' => self::REL_MODEL_CLIENT,
        'Manufacturer' => self::REL_MODEL_MANUFACTURER,
        'Order' => self::REL_MODEL_ORDER,
        'Storage' => self::REL_MODEL_STORAGE,
        'ProductPriceGroup' => self::REL_MODEL_PRODUCT_PRICE_GROUP,
        'ProductAttribute' => self::REL_MODEL_PRODUCT_ATTRIBUTE,
        'Unit' => self::REL_MODEL_UNIT,
        'ProductCategory' => self::REL_MODEL_PRODUCT_CATEGORY,
        'ProductPrice' => self::REL_MODEL_PRODUCT_PRICE,
        'ProductAnalogue' => self::REL_MODEL_PRODUCT_ANALOGUE,
        'Contract' => self::REL_MODEL_CONTRACT,
        'Discount' => self::REL_MODEL_DISCOUNT,
        'Cart' => self::REL_MODEL_CART
    ];

    public static $relModels = [
        self::REL_MODEL_PRODUCT => 'Продукт',
        self::REL_MODEL_POST => 'Пост',
        self::REL_MODEL_PAGE => 'Страница',
        self::REL_MODEL_USER => 'Пользователь',
        self::REL_MODEL_CLIENT => 'Клиент',
        self::REL_MODEL_MANUFACTURER => 'Производитель',
        self::REL_MODEL_ORDER => 'Заказ',
        self::REL_MODEL_STORAGE => 'Склад',
        self::REL_MODEL_PRODUCT_PRICE_GROUP => 'Тип цен',
        self::REL_MODEL_PRODUCT_ATTRIBUTE => 'Аттрибуты продукта из 1С',
        self::REL_MODEL_UNIT => 'Единицы измерения',
        self::REL_MODEL_PRODUCT_CATEGORY => 'Категории продуктов',
        self::REL_MODEL_PRODUCT_PRICE => 'Цены',
        self::REL_MODEL_PRODUCT_ANALOGUE => 'Аналоги продуктов',
        self::REL_MODEL_CONTRACT => 'Договора',
        self::REL_MODEL_DISCOUNT => 'Скидки',
        self::REL_MODEL_CART => 'Корзина'
    ];

    /**
     * Возвращает имя метода для получения связи
     * @param $className
     * @return null|string
     */
    public static function getMethodName($className)
    {
        $reflection = new \ReflectionClass($className);
        $modelName = $reflection->getShortName();

        if (!isset(ModelRelationHelper::$model[$modelName])) {
            return null;
        }

        $relModel = ModelRelationHelper::$model[$modelName] ?? null;

        $method = lcfirst($modelName);

        return $method;
    }

}