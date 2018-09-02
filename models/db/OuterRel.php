<?php

namespace app\models\db;

use Yii;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\Client;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "outer_rel".
 *
 * @property integer $id
 * @property string $guid
 * @property integer $relModel
 * @property integer $relModelId
 * @property Product $product
 * @property Client $client
 */
class OuterRel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outer_rel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relModel', 'relModelId'], 'integer'],
            [['guid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Title',
            'relModel' => 'Rel Model',
            'relModelId' => 'Rel Model ID',
        ];
    }

    /**
     * Возвращает продукт
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getProduct()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT) {
            return $this->hasOne(Product::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Возвращает продукт
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT_ATTRIBUTE) {
            return $this->hasOne(ProductAttribute::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }


    /**
     * Возвращает продукт
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getClient()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_CLIENT) {
            return $this->hasOne(Client::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Возвращает группу цен(прайс)
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getProductPriceGroup()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP) {
            return $this->hasOne(ProductPriceGroup::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Единица измерения
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getUnit()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_UNIT) {
            return $this->hasOne(Unit::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Категория продуктов
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY) {
            return $this->hasOne(ProductCategory::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Заказ
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getOrder()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_ORDER) {
            return $this->hasOne(Order::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     *  Склад
     *
     *
     * @return null|\yii\db\ActiveQuery
     */
    public function getStorage()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_STORAGE) {
            return $this->hasOne(Storage::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /** Юзер
     * @return null|\yii\db\ActiveQuery
     */
    public function getUser()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_USER) {
            return $this->hasOne(User::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /** Контракт
     * @return null|\yii\db\ActiveQuery
     */
    public function getContract()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_CONTRACT) {
            return $this->hasOne(Contract::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /** Производитель
     * @return null|\yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_MANUFACTURER) {
            return $this->hasOne(Manufacturer::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }
}
