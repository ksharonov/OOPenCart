<?php

namespace app\models\base;

use app\models\db\Product;
use app\system\base\Model;

/**
 * Class FavoriteItem
 *
 * Класс элемента избранного
 *
 * @package app\models\base
 */
class FavoriteItem extends Model
{
    public $favoriteId;
    public $productId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'productId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Продукт',
        ];
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return Product::findOne($this->productId);
    }
}