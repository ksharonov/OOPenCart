<?php

namespace app\models\base\product;

use app\models\db\Product;
use yii\base\BaseObject;

/**
 * Class ProductDelivery
 *
 * Класс по данным доставки продукта
 *
 * @package app\models\base\product
 * @property Product $product
 */
class ProductDelivery extends BaseObject
{
    /** @var Product */
    private $_product;

    /**
     * Установка продукта
     * @param Product $product
     */
    public function setProduct(Product &$product)
    {
        $this->_product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * Время поставки товара
     */
    public function getDeliveryTime()
    {
        if ($this->product->balance) {
            return date('d.m.Y');
        } else {
            return '15-45 дней';
        }
    }
}