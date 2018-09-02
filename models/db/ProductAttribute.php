<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\ProductToAttribute;
use yii\helpers\Json;
use app\models\OneC\ProductAttributeAliasOnec;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $id
 * @property string $title
 * @property integer $attributeGroupId
 * @property json $params
 * @property array $paramsArray
 * @property ProductAttributeGroup $group
 * @property string $name
 * @static array $attributeTypes
 */
class ProductAttribute extends \app\system\db\ActiveRecord
{
    const TYPE_FLAG_ONE = 0;
    const TYPE_FLAG_MANY = 1;
    const TYPE_SELECT_TEXT = 2;
    const TYPE_SELECT_NUMBER = 3;
    const TYPE_TEXT = 4;
    const TYPE_NUMBER = 5;

    public static $attributeTypes = [
        self::TYPE_FLAG_ONE => 'Флаг: один',
        self::TYPE_FLAG_MANY => 'Флаг: несколько',
        self::TYPE_SELECT_TEXT => 'Выбор: текст',
        self::TYPE_SELECT_NUMBER => 'Выбор: число',
        self::TYPE_TEXT => 'Число',
        self::TYPE_NUMBER => 'Текст'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'attributeGroupId'], 'required'],
            [['attributeGroupId', 'type'], 'integer'],
//            [['attributeGroupId'], 'integer', 'whenClient' => "function (attribute, value) {
//                console.log(attribute, value);
//            }"],
            [['title', 'params'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 64],
            [['name'], 'nameAttributeValidator'],
            [['name'], 'unique'],
        ];
    }

    /**
     * Валидатор поля имя
     */
    public function nameAttributeValidator($attribute)
    {
        if (!preg_match("/[[^a-z,A-Z]/", $this->$attribute)) {
            $this->addError($attribute, 'Имя должно быть на английском языке');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'attributeGroupId' => 'id группы атрибутов',
            'type' => 'Тип атрибута',
            'params' => 'Параметры',
            'name' => 'Имя атрибута'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {

        ProductToAttribute::deleteAll([
            'attributeId' => $this->id
        ]);

        return parent::beforeDelete();
    }

    /**
     * Возвращает группу атрибута
     *
     * @return ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ProductAttributeGroup::className(), ['id' => 'attributeGroupId']);
    }

    /**
     * Возвращает массив поля params
     *
     * @return array
     */
    public function getParamsArray()
    {
        if (is_array($this->params)) {
            return $this->params;
        } else {
            return Json::decode($this->params);
        }
    }

    /**
     * Возвращает показ количества в фильтрах
     *
     * @return array
     */
    public function testCount($category)
    {
        $arr = [];
        foreach ($this->paramsArray as $element) {
            $arr[$element] = ProductToAttribute::find()
                ->joinWith('product')
                ->joinWith('product.categories')
                ->where(['product_category.id' => $category->id])
                ->andWhere(['product_to_attribute.attrValue' => $element])
                ->andWhere(['product_to_attribute.attributeId' => $this->id])
                ->count();
        }
        return $arr;
    }

    /**
     * Возвращает список guid для 1С
     *
     * @return ActiveQuery
     */
    public function getGuidOneC()
    {
        return $this->hasOne(ProductAttributeAliasOnec::className(), ['attributeId' => 'id']);
    }
}
