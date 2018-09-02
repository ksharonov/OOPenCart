<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product_to_option_value".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $optionValueId
 * @property integer $optionId
 */
class ProductToOptionValue extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_to_option_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'optionValueId', 'optionId'], 'required'],
            [['productId', 'optionValueId', 'optionId'], 'integer'],
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
            'optionValueId' => 'Option Value ID',
            'optionId' => 'Option ID',
        ];
    }
}
