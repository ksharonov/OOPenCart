<?php

namespace app\models\base\product;

use app\helpers\ModelRelationHelper;
use app\models\base\Cart;
use app\models\db\Discount;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

/**
 * Class ProductDiscount
 * @package app\models\base\product
 *
 * @property Discount[] $all
 */
class ProductDiscount extends BaseObject
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
     * @return Discount|array|null|\yii\db\ActiveRecord
     */
    public function getPriority()
    {
        $discounts = $this->prepareQuery()->one();

        return $discounts;
    }

    /**
     * Значение скидки
     * @param $price
     * @return float|int
     */
    public function getValue($price)
    {
        $priorityPrice = $this->priority;

        if (!$priorityPrice) {
            return 0;
        }

        if ($priorityPrice->type == Discount::TYPE_VALUE) {
            return $priorityPrice->value;
        } elseif ($priorityPrice->type = Discount::TYPE_PERCENT) {
            return ($price / 100 * $priorityPrice->value);
        }

    }

    /**
     * @return Discount[]|array|\yii\db\ActiveRecord[]
     */
    public function getAll()
    {
        $discounts = $this->prepareQuery()->all();

        return $discounts;
    }

    /**
     * Подготовка запроса
     * @return ActiveQuery
     */
    public function prepareQuery()
    {
        $productId = $this->_product->id;

        $productToCategory = ProductToCategory::find()
            ->select('categoryId')
            ->where(['productId' => $productId]);

        $query = Discount::find()
//            ->select('*, `params`->"$.sum" as `sum`')
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
                'relModelId' => $productId
            ])
            ->orWhere([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY,
                'relModelId' => $productToCategory
            ]);

        return $query;
    }
}