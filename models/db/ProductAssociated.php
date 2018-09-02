<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;

/**
 * This is the model class for table "product_associated".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $productAssociatedId
 */
class ProductAssociated extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_associated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'productAssociatedId'], 'integer'],
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
            'productAssociatedId' => 'Product Associated ID',
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
     * Возвращает сопутствующий продукт
     *
     * @return ActiveQuery
     */
    public function getAssocProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productAssociatedId']);
    }
}
