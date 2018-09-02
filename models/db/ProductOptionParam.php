<?php

namespace app\models\db;

use Yii;
use yii\helpers\Json;
use \yii\db\ActiveQuery;

/**
 * This is the model class for table "product_option_param".
 *
 * @property integer $id
 * @property integer $productId
 * @property Json $povs
 * @property string $qnt
 * @property string $price
 * @property string $weight
 * @property Product $product
 * @property array $optionValueIds
 * @property ProductOptionValue[] $optionValues
 */
class ProductOptionParam extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_option_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId'], 'required'],
            [['productId'], 'integer'],
            [['povs'], 'string'],
            [['qnt', 'price', 'weight'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Ид товара',
            'povs' => 'Опции',
            'qnt' => 'Количество',
            'price' => 'Цена',
            'weight' => 'Вес',
            'product' => 'Товар'
        ];
    }

    /**
     * Возвращает товар
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Вовзвращает список id значений опций
     *
     * @return mixed
     */
    public function getOptionValueIds()
    {
        return Json::decode($this->povs);
    }

    /**
     * Выставляет список id значений опций
     *
     * @param $options
     * @return bool
     */
    public function setOptionValueIds($options)
    {
        $this->povs = Json::encode($options);
        return true;
    }

    /**
     * Возвращает значения опций из $povs
     *
     * @return ProductOptionValue[]
     */
    public function getOptionValues()
    {
        $optionValueIds = $this->optionValueIds;

        return ProductOptionValue::findAll($optionValueIds);
    }
}
