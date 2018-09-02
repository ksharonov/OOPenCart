<?php

namespace app\models\db;

use Yii;
use app\models\db\ProductFilterFastParam;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "product_filter_fast".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property integer $categoryId
 * @property integer $expanded
 * @property string $params
 * @property ProductAttribute[] $attrs
 */
class ProductFilterFast extends \app\system\db\ActiveRecord
{
    public function beforeSave($insert)
    {
        $this->expanded = 1;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_filter_fast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId', 'expanded'], 'integer'],
            ['params', 'string'],
            [['title'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 64],
            [['name'], 'nameAttributeValidator'],
            [['name'], 'unique'],
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
            'name' => 'Имя',
            'categoryId' => 'Категория',
            'expanded' => 'Отображение',
            'params' => 'Параметры'
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
     * Возвращает атрибуты этого фильтра
     *
     * @return ActiveQuery
     */
    public function getAttrs()
    {
        return $this->hasMany(ProductFilterFastParam::className(), ['productFilterFastId' => 'id'])
            ->orderBy('attributeId ASC');
    }

    /**
     * Возвращает родительскую группу
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'categoryId']);
    }
}
