<?php

namespace app\models\db;

use app\components\NumberComponent;
use app\helpers\NumberHelper;
use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;
use app\models\db\ProductPriceGroup;
use app\models\db\ProductOptionParam;
use NumberFormatter;

/**
 * This is the model class for table "product_price".
 *
 * Модель для таблицы цен
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $productPriceGroupId
 * @property double $value
 * @property integer $popId
 * @property string $dtStart
 * @property string $dtEnd
 * @property string $val
 * @property Product $product
 * @property ProductPriceGroup $productPriceGroup
 * @property ProductOptionParam $productOptionParam
 */
class ProductPrice extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'productPriceGroupId', 'popId'], 'integer'],
            [['value'], 'double'],
            [['dtStart', 'dtEnd'], 'safe'],
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
            'productPriceGroupId' => 'Группа цены',
            'value' => 'Значение',
            'dtStart' => 'Дата начала действия цены',
            'dtEnd' => 'Дата окончения действия цены',
            'popId' => 'id таблицы параметров'
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
     * Возвращает группу атрибута
     *
     * @return ActiveQuery
     */
    public function getProductPriceGroup()
    {
        return $this->hasOne(ProductPriceGroup::className(), ['id' => 'productPriceGroupId']);
    }

    /**
     * Возвращает группу атрибута
     *
     * @return ActiveQuery
     */
    public function getProductOptionParam()
    {
        return $this->hasOne(ProductOptionParam::className(), ['id' => 'popId']);
    }

    // TODO
    /**
     * Цена в денежном формате
     * @return mixed
     * @deprecated Использовать value с NumberHelper
     */
    public function getVal()
    {
        return $this->value;
    }
}
