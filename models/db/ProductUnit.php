<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "product_unit".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $unitId
 * @property integer $rate
 * @property Product $product
 * @property Unit $unit
 */
class ProductUnit extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'unitId', 'rate'], 'integer'],
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
            'unitId' => 'ЕИ',
            'rate' => 'Коэффициент',
        ];
    }

    /**
     * Возвращает товар
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Возвращает единицу измерения
     *
     * @return ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unitId']);
    }
}
