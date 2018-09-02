<?php

namespace app\models\db;

use Yii;
use app\models\db\ProductAttribute;
use app\models\db\Product;
use yii\db\ActiveQuery;


/**
 * This is the model class for table "product_to_attribute".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $attributeId
 * @property string $attrValue
 * @property Product $product
 * @property ProductAttribute $attr
 */
class ProductToAttribute extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'attributeId'], 'required'],
            [['productId', 'attributeId'], 'integer'],
            [['attrValue'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'id продукта',
            'attributeId' => 'id атрибута',
            'attrValue' => 'Значение',
        ];
    }

    /**
     * Возвращает группу атрибута
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Возвращает атрибут
     *
     * @return ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'attributeId']);
    }
}
