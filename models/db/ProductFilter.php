<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\ProductCategory;
use app\models\db\ProductAttribute;


/**
 * This is the model class for table "product_filter".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property integer $source
 * @property integer $sourceId
 * @property integer $position
 * @property integer $expanded
 * @property integer $type
 * @property string $params
 * @property ProductCategory[] $categories
 * @property array $filterLink
 * @property ProductAttribute $filterParams
 * @static array $sources
 * @static array $expendedStatuses
 */
class ProductFilter extends \app\system\db\ActiveRecord
{

    const SOURCE_FIELD = 0;
    const SOURCE_ATTRIBUTE = 1;
    const SOURCE_OPTION = 2;

    const EXPENDED_HIDE = 0;
    const EXPENDED_SHOW = 1;

    const TYPE_RANGER = 0;
    const TYPE_CHECKBOX = 1;
    const TYPE_INPUT = 2;
    const TYPE_RADIO = 3;
    const TYPE_SELECT = 4;

    public static $sources = [
//        self::SOURCE_FIELD => 'Поле товара', //Временно убраны
        self::SOURCE_ATTRIBUTE => 'Атрибут товара',
//        self::SOURCE_OPTION => 'Опция товара'
    ];

    public static $expendedStatuses = [
        self::EXPENDED_HIDE => 'Скрывать',
        self::EXPENDED_SHOW => 'Показывать'
    ];

    public static $types = [
        self::TYPE_RANGER => 'Ползунок',
        self::TYPE_CHECKBOX => 'Чекбокс',
        self::TYPE_INPUT => 'Инпут',
        self::TYPE_RADIO => 'Радио',
        self::TYPE_SELECT => 'Выбор'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source', 'sourceId', 'position', 'expanded', 'type'], 'integer'],
            [['params'], 'string'],
            [['title'], 'string', 'max' => 128]
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
            'source' => 'Источник',
            'sourceId' => 'id-источника',
            'position' => 'Вес в списке',
            'expanded' => 'Отображение',
            'params' => 'Дополнительные параметры',
            'type' => 'Тип'
        ];
    }

    /**
     * Возвращает массив категорий, к которому привязан данный фильтр
     *
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'categoryId'])
            ->viaTable('product_filter_to_category', ['filterId' => 'id']);
    }

    /**
     * Возвращает связанный элемент фильтра
     *
     * @return ActiveQuery
     */
    public function getFilterParams()
    {
        if ($this->source == self::SOURCE_ATTRIBUTE) {
            return $this->hasOne(ProductAttribute::className(), ['id' => 'sourceId']);
        }
    }

    /**
     * Возвращает атрибут
     *
     * @return ActiveQuery
     */
    public function getAttr()
    {
        if ($this->source == self::SOURCE_ATTRIBUTE) {
            return $this->hasOne(ProductAttribute::className(), [
                'id' => 'sourceId'
            ]);
        } else {
            return null;
        }
    }

    /**
     * Возвращает источник фильтра
     *
     * @return ActiveQuery
     */
    public function getSource()
    {
        return $this->attr;
    }

}
