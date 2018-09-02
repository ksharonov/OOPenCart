<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;

/**
 * This is the model class for table "product_to_category".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $categoryId
 * @property Product $product
 * @property ProductCategory $category
 */
class ProductToCategory extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'categoryId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Product ID',
            'categoryId' => 'Category ID',
        ];
    }

    /**
     * Возвращает продукт
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Возвращает категорию
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'categoryId']);
    }
}
