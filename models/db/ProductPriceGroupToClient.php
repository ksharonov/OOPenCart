<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product_price_group_to_client".
 *
 * Модель связующей таблицы Клиента и Прайса
 *
 * @property integer $id
 * @property integer $productPriceGroupId
 * @property integer $clientId
 */
class ProductPriceGroupToClient extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price_group_to_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productPriceGroupId', 'clientId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productPriceGroupId' => 'Product Price Group ID',
            'clientId' => 'Client ID',
        ];
    }
}
