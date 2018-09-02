<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product_option_value".
 *
 * @property integer $id
 * @property integer $optionId
 * @property string $value
 * @property ProductOption $option
 */
class ProductOptionValue extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_option_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['optionId', 'value'], 'required'],
            [['optionId'], 'integer'],
            [['value'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'optionId' => 'Ид опции',
            'value' => 'Значение',
        ];
    }

    /**
     * Возвращает опцию, связанную с этим значением
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOption()
    {
        return $this->hasOne(ProductOption::className(), ['id' => 'optionId']);
    }

    public function beforeDelete()
    {
        Product::deleteOptionParamValueLinks($this->id);
        ProductToOptionValue::deleteAll(['optionValueId' => $this->id]);

        return parent::beforeDelete();
    }
}
