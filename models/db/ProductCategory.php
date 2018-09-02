<?php

namespace app\models\db;

use app\models\behaviors\ProductCategorySqlBehavior;
use app\models\traits\DiscountTrait;
use app\models\traits\FileTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveQuery;
use app\helpers\ModelRelationHelper;


/**
 * This is the model class for table "product_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $dtUpdate
 * @property string $dtCreate
 * @property integer $parentId
 * @property integer $manyAmount
 * @property ProductCategory $parent
 * @property ProductCategory[] $childs
 * @property ProductFilter[] $filters
 * @property string $link
 * @property string $categoryLink
 * @property integer $productsCount
 * @property string $thumbnail
 * @property integer $status
 * @property boolean $isDefault
 */
class ProductCategory extends \app\system\db\ActiveRecord
{
    use DiscountTrait;
    use FileTrait;

    const STATUS_HIDDEN = 0;
    const STATUS_PUBLISHED = 1;

    /** @var array статусы поста */
    public static $statuses = [
        self::STATUS_HIDDEN => 'Скрыт',
        self::STATUS_PUBLISHED => 'Опубликован'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dtCreate',
                'updatedAtAttribute' => 'dtUpdate',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => ProductCategorySqlBehavior::className(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['dtUpdate', 'dtCreate'], 'safe'],
            [['parentId', 'manyAmount', 'status'], 'integer'],
            [['isDefault'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['thumbnail'], 'string']
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
            'parentId' => 'Родительская группа',
            'content' => 'Содержимое',
            'manyAmount' => 'Количество для "Много"',
            'thumbnail' => 'Изображение',
            'status' => 'Статус',
            'isDefault' => 'Стандартная категория'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {

        ProductToCategory::deleteAll([
            'categoryId' => $this->id
        ]);

        return parent::beforeDelete();
    }

    /**
     * Возвращает родительскую группу
     *
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'parentId']);
    }

    /**
     * Возвращает дочерние группы
     *
     * @return ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(ProductCategory::className(), ['parentId' => 'id']);
    }

    /**
     * @return bool
     */
    public function hasChild()
    {
        return (bool)$this->hasMany(ProductCategory::className(), ['parentId' => 'id'])->count();
    }

    /**
     * Количество товаров в данной категории
     */
    public function getProductsCount()
    {
        $count = ProductToCategory::find()->where(['categoryId' => $this->id])->count();
        return $count;
    }

    /**
     * Возвращает массив фильтров, которые привязаны к категории
     *
     * @return ActiveQuery
     */
    public function getFilters()
    {
        return $this->hasMany(ProductFilter::className(), ['id' => 'filterId'])
            ->viaTable('product_filter_to_category', ['categoryId' => 'id']);
    }

    /**
     * Возвращает ссылку на категорию
     *
     * @return string
     */
    public function getLink()
    {
        /* if ($this->hasChild()) {
             return $this->categoryLink;
         }*/
        // Исправил под sql. Алексей 18.04.18
//        dump(11);
        if ($this->sqlChilds) {
            return $this->categoryLink;
        }
        return Yii::$app->urlManager->createAbsoluteUrl(['/catalog/index', 'ProductSearch[category]' => $this->id]);
    }

    /**
     * Ссылка на список дочерних категорий
     */
    public function getCategoryLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl('/catalog/category/' . $this->id);
    }

    /**
     * Возвращает продукты этой категории
     *
     * @return ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'productId'])
            ->viaTable('product_to_category', ['categoryId' => 'id']);
    }
}
