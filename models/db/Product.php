<?php

namespace app\models\db;

use app\components\ClientComponent;
use app\models\base\product\ProductDiscount;
use app\models\behaviors\ProductSqlBehavior;
use app\models\traits\DiscountTrait;
use app\models\traits\FileTrait;
use app\system\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\db\ProductAnalogue;
use app\models\db\File;
use app\models\base\product\ProductPrice;
use app\models\db\ProductPrice as ProductPriceDB;
use app\models\db\ProductToAttribute;
use app\models\db\ProductToCategory;
use app\models\db\ProductUnit;
use app\models\db\Manufacturer;
use app\models\db\ProductReview;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;

/**
 * This is the model class for table "product".
 *
 * Модель для таблицы продуктов
 *
 * @property integer $id
 * @property integer $defaultPrice
 * @property string $title
 * @property string $content
 * @property string $smallDescription
 * @property string $dtUpdate
 * @property string $dtCreate
 * @property string $params
 * @property string $slug
 * @property string $vendorCode
 * @property integer $status
 * @property integer $rating
 * @property integer $manufacturerId
 * @property integer $manyAmount
 * @property boolean $isBest
 * @property boolean $isNew
 * @property boolean $isDiscount
 * @property ProductCategory[] $categories
 * @property ProductAttribute[] $attrs
 * @property ProductOptionParam[] $optionParams
 * @property ProductPrice $price
 * @property ProductPriceDB[] $prices
 * @property ProductOptionValue[] $optionValues
 * @property ProductOption[] $options
 * @property ProductAnalogue[] $productAnalogues
 * @property ProductAssociated[] $productAssocs
 * @property integer $reviewsCount
 * @property File[] $images
 * @property File[] $certificates
 * @property File $mainImage
 * @property ProductReview[] $reviews
 * @property Seo $seo
 * @property Manufacturer $manufacturer
 * @property array $data
 * @property string $link
 * @property string $categoriesString
 * @property OuterRel $outerRel
 * @property boolean $fromRemote
 * @property StorageBalance $balances
 * @property integer $balance
 * @property string $backCode
 * @property ProductDiscount $discount
 * @static array $filteredFields
 * @static array $statuses
 */
class Product extends \app\system\db\ActiveRecord
{
    //use DiscountTrait;
    use FileTrait;

    const STATUS_CLEARED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DELETED = 2;
    /**
     * @var bool
     * @deprecated
     */
    public $from1C = false;

    /**
     * Товар добавлен с удалённой системы (Н-р: 1с/lexema)
     * @var bool
     */
    public $fromRemote = false;

    /** @var */
    public $defaultPrice;

    /** @var integer */
    public $_price = null;

    /** @var Client */
    public $_client;

    public static $statuses = [
        self::STATUS_CLEARED => 'Черновик',
        self::STATUS_PUBLISHED => 'Опубликовано',
        self::STATUS_DELETED => 'Удалена'
    ];

    public static $filteredFields = [
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * Product constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->defaultPrice = Setting::get('DEFAULT.PRICE.VALUE');
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->dtCreate == $this->dtUpdate && $this->status === self::STATUS_DELETED) {
            $this->fromRemote = true;
        }

        if ($this->dtCreate == $this->dtUpdate && !$this->fromRemote) {
            $this->status = self::STATUS_PUBLISHED;
        }

        if ($this->isNewRecord && !$this->fromRemote) {
            $this->status = self::STATUS_CLEARED;
        }

        if ($this->fromRemote) {
            $this->installAfterImportFrom1C();
        }

        parent::beforeSave($insert);
        return true;
    }

    /**
     * Настройка товара после импорта 1С
     */
    public function installAfterImportFrom1C()
    {

    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $isNewRecord = array_key_exists('id', $changedAttributes) && !$changedAttributes['id'];

        if ($isNewRecord && !$this->_price) {
            $this->price->prepareIn(true);
        }

        if ($isNewRecord && $this->_price) {
            $this->price->prepareIn(false);
        }

        if ($isNewRecord) {
            $this->setDefaultSeo();
        }

        $this->price->default = $this->defaultPrice;

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
//        if (!$this->params) {
//            $this->params = Json::encode([]);
//        }

        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        ProductAnalogue::deleteAll(['productId' => $this->id]);

        File::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'relModelId' => $this->id
        ]);

        ProductPriceDB::deleteAll(['productId' => $this->id]);
        ProductToAttribute::deleteAll(['productId' => $this->id]);
        ProductToCategory::deleteAll(['productId' => $this->id]);
        ProductUnit::deleteAll(['productId' => $this->id]);
        ProductToOptionValue::deleteAll(['productId' => $this->id]);
        ProductOptionParam::deleteAll(['productId' => $this->id]);

        Seo::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'relModelId' => $this->id
        ]);

        ProductAnalogue::deleteAll([
            'productId' => $this->id
        ]);

        ProductAnalogue::deleteAll([
            'productAnalogueId' => $this->id
        ]);

        ProductAssociated::deleteAll([
            'productId' => $this->id
        ]);

        ProductAssociated::deleteAll([
            'productAssociatedId' => $this->id
        ]);

        ProductUnit::deleteAll([
            'productId' => $this->id
        ]);

        OuterRel::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'relModelId' => $this->id
        ]);

        StorageBalance::deleteAll([
            'productId' => $this->id
        ]);

        ProductReview::deleteAll([
            'productId' => $this->id
        ]);

        return parent::beforeDelete();
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
                'class' => ProductSqlBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'categories', 'params'], 'string'],
            [['status', 'manufacturerId', 'manyAmount', 'id', 'backCode'], 'integer'],
            [['defaultPrice'], 'double'],
            [['dtUpdate', 'dtCreate'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['smallDescription'], 'string', 'max' => 70],
            [['vendorCode'], 'string', 'max' => 64],
            //[['slug'], 'slugValidator'],
            [['slug'], 'unique'],
            [['title'], 'required', 'on' => 'update'],
            //[['slug'], 'string', 'max' => 255],
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
            'content' => 'Содержимое',
            'dtUpdate' => 'Дата обновления',
            'dtCreate' => 'Дата создания',
            'defaultPrice' => 'Цена',
            'status' => 'Статус',
            'categories' => 'Категории',
            'manufacturerId' => 'Производитель',
            'manyAmount' => 'Количество для "Много"',
            'isBest' => 'Хиты продаж',
            'isNew' => 'Новое',
            'isDiscount' => 'Акции',
            'params' => 'Параметры',
            'smallDescription' => 'Краткое описание',
            'vendorCode' => 'Артикул',
            'slug' => 'Название ссылки',
            'backCode' => 'Код товара'
        ];
    }

    /**
     * Валидатор поля slug
     */
    public function slugValidator($attribute)
    {
        if (!preg_match("/[[^a-z,A-Z]/", $this->$attribute)) {
            $this->addError($attribute, 'Поле должно быть на английском языке');
        }
    }

    /*
     * Возвращает доступную минимальную информацию о продукте
     * @return array
     */
    public function getData()
    {
        return [
            'title' => $this->title,
            'manufacturer' => $this->manufacturer->title ?? null
        ];
    }

    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['id' => 'unitId'])
            ->viaTable('product_unit', ['productId' => 'id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unitId'])
            ->viaTable('product_unit', ['productId' => 'id']);
    }

    /**
     * Возвращает категории этого товара
     *
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['id' => 'categoryId'])
            ->viaTable('product_to_category', ['productId' => 'id']);
    }

    /**
     * Возвращает аналоги этого товара
     *
     * @return ActiveQuery
     */
    public function getProductAnalogues()
    {
        return $this->hasMany(Product::className(), ['id' => 'productAnalogueId'])
            ->viaTable('product_analogue', ['productId' => 'id']);
    }

    /**
     * Возвращает сопутствующие товары этого товара
     *
     * @return ActiveQuery
     */
    public function getProductAssocs()
    {
        return $this->hasMany(Product::className(), ['id' => 'productAssociatedId'])
            ->viaTable('product_associated', ['productId' => 'id']);
    }

    public function getCategoriesString()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            array_push($categories, $category->title);
        }
        return implode(", ", $categories);
    }

    /**
     * Возвращает атрибуты этого товара
     *
     * @return ActiveQuery
     */
    public function getAttrs()
    {
        return $this->hasMany(ProductToAttribute::className(), ['productId' => 'id'])
            ->orderBy('attributeId ASC');
    }


    /**
     * Возвращает OptionParams этого товара
     *
     * @return ActiveQuery
     */
    public function getOptionParams()
    {
        return $this->hasMany(ProductOptionParam::className(), ['productId' => 'id']);
    }


    /**
     * Возвращает связанные с этим товаром optionValues
     *
     * @return ActiveQuery
     */
    public function getOptionValues()
    {
        return $this->hasMany(ProductOptionValue::className(), ['id' => 'optionValueId'])
            ->viaTable('product_to_option_value', ['productId' => 'id']);
    }

    /**
     * Возвращает связанные с этим товаром options
     *
     * @return ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(ProductOption::className(), ['id' => 'optionId'])
            ->viaTable('product_to_option_value', ['productId' => 'id'])->distinct();
    }

    /**
     * Возвращает файлы
     *
     * @return ActiveQuery
     */
    /*
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ])
            ->andWhere(['<>', 'type', File::TYPE_IMAGE]);
    }
    */

    /**
     * Возвращает сертификаты
     *
     * @return ActiveQuery
     */
    /*
    public function getCertificates()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'type' => File::TYPE_CERTIFICATE,
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ]);
    }
    */

    /**
     * Возвращает изображения
     *
     * @return ActiveQuery
     */
    /*
    public function getImages()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'type' => File::TYPE_IMAGE,
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ]);
    }
    */

    /**
     * Возвращает главное изображение
     *
     * @return ActiveQuery|null|\stdClass
     */
    /*
    public function getMainImage()
    {
        $emptyImage = new \stdClass();
        $emptyImage->path = '/images/emptyProduct.jpg';

        return $this->image ?? $emptyImage ?? null;
    }
    */

    /*
    public function getImage()
    {
        return $this->hasOne(File::className(), ['relModelId' => 'id'])
            ->where([
                'type' => File::TYPE_IMAGE,
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ])
            ->orderBy('id ASC');
    }
    */

    /**
     * Возвращает заказы этого продукта
     *
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(OrderStatusHistory::className(), ['productId' => 'id']);
    }

    /**
     * Возвращает производителя товара
     *
     * @return ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturerId']);
    }

    /**
     * Возвращает SEO-модель продукта
     *
     * @return ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ]);
    }

    /**
     * Устанавливает продукт и цену для определённого клиента
     * //todo возможно на вход давать несколько клиентов
     *
     * @param Client | Client[] | boolean $client
     */
    public function setClient($client)
    {
        if ($client instanceof Client || is_bool($client)) {
            $this->_client = $client;
        }
    }

    /**
     * Устанавливает стандартную SEO
     *
     * @return void
     */
    public function setDefaultSeo()
    {
        $model = Seo::findOne([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'relModelId' => $this->id
        ]);

        if (!$model) {
            $model = new Seo();
            $model->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
            $model->relModelId = $this->id;
            $model->save();
        }
    }

    /**
     * Устанавливаем цену
     *
     * @param $price
     */
    public function setPrice(int $price)
    {
        $this->_price = $price;
    }

    /**
     * Возвращает объект класса получения цен
     * @return \app\models\base\product\ProductPrice
     */
    public function getPrice()
    {
        $productPrice = new \app\models\base\product\ProductPrice();
        $productPrice->setProduct($this);
        return $productPrice;
    }

    /**
     * Возвращает объект класса доставки продукта
     * @return \app\models\base\product\ProductDelivery
     */
    public function getDelivery()
    {
        $productDelivery = new \app\models\base\product\ProductDelivery();
        $productDelivery->setProduct($this);
        return $productDelivery;
    }

    /**
     * @return \app\models\base\product\ProductDiscount
     */
    public function getDiscount()
    {
        $productDiscount = new ProductDiscount();
        $productDiscount->setProduct($this);
        return $productDiscount;
    }

    /**
     * Возвращает все цены этого продукта
     *
     * @return ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(ProductPriceDB::className(), ['productId' => 'id'])
            ->from('product_price ppu')
            ->joinWith('productPriceGroup')
            ->joinWith('productPriceGroup.productPriceGroupToClient')
            ->where('ppu.popId is NULL')
            ->orderBy('product_price_group.priority ASC');
    }

    public function getPriceRel()
    {
        return $this->hasMany(ProductPriceDB::className(), ['productId' => 'id']);
    }

    /**
     * Возвращает рейтинг товара
     *
     * @return integer
     */
    public function getRating()
    {
        return (int)$this->hasOne(ProductReview::className(), ['productId' => 'id'])
                ->where(['status' => ProductReview::STATUS_ACTIVE])
                ->average('rating') ?? null;
    }

    /**
     * Возвращает количество проголосовавших
     *
     * @return integer
     */
    public function getReviewsCount()
    {
        return (int)$this->hasOne(ProductReview::className(), ['productId' => 'id'])
                ->where(['status' => ProductReview::STATUS_ACTIVE])
                ->count() ?? null;
    }

    /**
     * Возвращает отзывы
     *
     * @return ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(ProductReview::className(), ['productId' => 'id'])
            ->where(['status' => ProductReview::STATUS_ACTIVE]);
    }

    /**
     * Удаляет ссылки на значение $optionId povs у всех товаров
     *
     * @param $optionValueId
     */
    public static function deleteOptionParamValueLinks($optionValueId)
    {
        $optionValueId = (int)$optionValueId;

        $optionParams = ProductOptionParam::find()->where("JSON_CONTAINS(povs, '$optionValueId')")->all();
        foreach ($optionParams as $optionParam) {
            /* @var ProductOptionParam $optionParam */
            $povs = Json::decode($optionParam->povs);
            ArrayHelper::removeValue($povs, $optionValueId);
            $optionParam->povs = Json::encode($povs);
            $optionParam->save();
        }
    }

    /**
     * Возвращает ссылку на товар
     *
     * @return string
     */
    public function getLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/product/' . $this->slug . '/']);
    }

    /**
     * Возвращает модель OuterRel (внешняя связь по уникальному идентификатору продукта)
     *
     * @return ActiveQuery
     */
    public function getOuterRel()
    {
        return $this->hasOne(OuterRel::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ]);
    }

    /**
     * Получение остатков данного продукта
     *
     * @return ActiveQuery
     */
    public function getBalances()
    {
        return $this->hasMany(StorageBalance::className(), [
            'productId' => 'id'
        ])
            ->joinWith('storage')
            ->where('popId is NULL')
            ->andWhere(['storage.status' => Storage::STATUS_ACTIVE])
            ->orderBy('storageId');
    }

    /**
     * Получить количество остатков
     */
    public function getBalance()
    {
        // TODO (int) не подходит, т.к. могут быть не целые числа
//        return (int)$this->hasMany(StorageBalance::className(), [
        return ($this->hasMany(StorageBalance::className(), [
                'productId' => 'id'
            ])
                ->where(['state' => StorageBalance::STATE_IN_STOCK])
                ->sum('quantity')) + 0 ?? 0;
    }

    /**
     * Товар - лучшее предложение?
     * @return bool
     */
    public function getIsBest()
    {
        $newId = Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID');
        $isNew = (bool)self::find()
            ->joinWith('categories')
            ->where(['product_to_category.categoryId' => $newId])
            ->andWhere(['product_to_category.productId' => $this->id])
            ->count();
        return $isNew;
    }

    /**
     * Товар - новый
     * @return bool
     */
    public function getIsNew()
    {
        $newId = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
        $isNew = (bool)self::find()
            ->joinWith('categories')
            ->where(['product_to_category.categoryId' => $newId])
            ->andWhere(['product_to_category.productId' => $this->id])
            ->count();
        return $isNew;
    }

    /** Товар - скидочный
     * @return bool
     * @throws \yii\base\Exception
     */
    public function getIsDiscount()
    {
        $salesId = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
        $isDiscount = (bool)self::find()
            ->joinWith('categories')
            ->where(['product_to_category.categoryId' => $salesId])
            ->andWhere(['product_to_category.productId' => $this->id])
            ->count();

        return $isDiscount;
    }
}
