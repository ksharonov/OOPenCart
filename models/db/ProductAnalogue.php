<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "product_analogue".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $productAnalogueId
 * @property integer $backcomp
 * @property Product $product
 * @property Product $productAnalogue
 */
class ProductAnalogue extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_analogue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'productAnalogueId', 'backcomp'], 'integer'],
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
            'productAnalogueId' => 'Product Analogue ID',
            'backcomp' => 'Обр. совместимость',
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
     * Возвращает аналог продукта
     *
     * @return ActiveQuery
     */
    public function getProductAnalogue()
    {
        return $this->hasOne(Product::className(), ['id' => 'productAnalogueId']);
    }
}
