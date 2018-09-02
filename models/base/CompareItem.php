<?php

namespace app\models\base;

use app\models\db\Product;
use app\system\base\Model;

/**
 * Class CompareItem
 *
 * Элемент сравнения
 *
 * @package app\models\base
 */
class CompareItem extends Model
{
    public $productId;

    public $attributes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sum'], 'integer'],
            [['items'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'items' => 'Содержимое',
            'userId' => 'Клиент'
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