<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\ProductFilterFast;
use app\models\db\ProductAttribute;

/**
 * This is the model class for table "product_filter_fast_param".
 *
 * @property integer $id
 * @property integer $productFilterFastId
 * @property integer $attributeId
 * @property string $attrValue
 * @property ProductFilterFast $filterFast
 * @property ProductAttribute $attr
 */
class ProductFilterFastParam extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_filter_fast_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productFilterFastId', 'attributeId'], 'integer'],
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
            'productFilterFastId' => 'Product Filter Fast ID',
            'attributeId' => 'Attribute ID',
            'attrValue' => 'Attr Value',
        ];
    }

    /**
     * Возвращает группу атрибута
     *
     * @return ActiveQuery
     */
    public function getFilterFast()
    {
        return $this->hasOne(ProductFilterFast::className(), ['id' => 'productFilterFastId']);
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
