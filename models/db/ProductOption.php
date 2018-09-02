<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "product_option".
 *
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property ProductOptionValue[] $values
 * @static array $productOptionTypes
 */
class ProductOption extends \app\system\db\ActiveRecord
{
    const TYPE_DROPDOWN = 0;
    const TYPE_RADIOBUTTON = 1;
    const TYPE_BADGE = 2;

    public static $productOptionTypes = [
        self::TYPE_DROPDOWN => 'Выпадающий список',
        self::TYPE_RADIOBUTTON => 'Радио кнопки',
        self::TYPE_BADGE => 'Значок'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['type'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'type' => 'Тип',
        ];
    }

    /**
     * Возвращает все значения этой опции
     *
     * @return ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(ProductOptionValue::className(), ['optionId' => 'id']);
    }


    /**
     * Возвращает все товары, которые имеют эту опцию
     *
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'productId'])
            ->viaTable('product_to_option_value', ['optionId' => 'id'])->distinct();
    }

    public function beforeDelete()
    {
        ProductOptionValue::deleteAll(['optionId' => $this->id]);
        ProductToOptionValue::deleteAll(['optionId' => $this->id]);

        return parent::beforeDelete();
    }
}
