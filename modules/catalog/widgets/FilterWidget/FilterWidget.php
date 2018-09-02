<?php

namespace app\modules\catalog\widgets\FilterWidget;

use app\models\db\CityOnSite;
use app\models\db\ProductToCategory;
use app\models\db\Setting;
use app\modules\catalog\widgets\FilterWidget\models\ProductSearch;
use Yii;
use app\models\db\ProductCategory;
//use yii\base\Widget;
use app\system\base\Widget;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\web\Request;
use app\models\db\Product;
use app\models\db\ProductFilter;
use app\modules\catalog\widgets\FilterWidget\FilterAsset;
use app\modules\catalog\widgets\FilterWidget\filters\FilterCheckbox\FilterCheckbox;
use app\models\db\Manufacturer;

/**
 * Виджет списка категорий
 * @property Product $model
 * @property ProductCategory $_category
 * @property Request $_request
 * @property array $_filters
 * @static array $widgetByType
 *
 */
class FilterWidget extends Widget
{
    public $model;

    public $searchModelName = 'ProductSearch';

    private $_category;

    private $_manufacturer;

    /**
     * @var array
     */
    private $_request = [];

    /**
     * @var array Массив фильтров
     */
    private $_filters = [];

    /**
     * @var array Массив производителей
     */
    private $_manufacturers = [];


    private $_categories = [];

    /**
     * @var bool
     */
    public $enableManufacturerFilter = true;

    /**
     * Массив городов
     * @var CityOnSite[]
     */
    private $_cities = [];

    private $_search = false;


    public static $widgetByType = [];

    /**
     * Инициализация виджета
     *
     * @return void
     */
    public function init()
    {
        self::$widgetByType[ProductFilter::TYPE_RANGER] = FilterCheckbox::className();
        self::$widgetByType[ProductFilter::TYPE_CHECKBOX] = FilterCheckbox::className();
        self::$widgetByType[ProductFilter::TYPE_INPUT] = FilterCheckbox::className();
        self::$widgetByType[ProductFilter::TYPE_RADIO] = FilterCheckbox::className();
        self::$widgetByType[ProductFilter::TYPE_SELECT] = FilterCheckbox::className();

        $this->getData();
    }

    /**
     * Получение основных данных для генерации фильтров
     *
     * @return void
     */
    public function getData()
    {
        $this->_request = Yii::$app->request->get($this->searchModelName) ?? null;

        $categoryId = $this->_request['category'] ?? null;

        $this->_category = ProductCategory::findOne(['id' => $categoryId]) ?? null;


        $this->_filters = $this->_category->filters ?? [];

        if ($this->enableManufacturerFilter) {
            $this->prepareManufacturerFilter();
        } else {
            $manufacturerId = $this->_request['manufacturerFilter'] ?? null;
            $this->_manufacturer = Manufacturer::findOne(['id' => $manufacturerId]) ?? null;
        }

        $this->_search = $this->_request['search'] ?? false;

        $this->prepareBalanceFilter();
        $this->prepareCityFilter();
        $this->prepareCategories();
        $this->prepareUniqueLists();
    }

    public function run()
    {
        $view = $this->getView();
        FilterAsset::register($view);

//        if ($this->_category)
        return $this->render('index', [
            'searchModelName' => $this->searchModelName,
            'category' => $this->_category,
            'manufacturer' => $this->_manufacturer,
            'filters' => $this->_filters,
            'manufacturers' => $this->_manufacturers,
            'cities' => $this->_cities,
            'search' => $this->_search,
            'categories' => $this->_categories
        ]);
    }

    /**
     * Подготовка данных к фильтрации производителей
     */
    public function prepareManufacturerFilter()
    {
        $category = $this->_category->id ?? null;

        $actionId = Yii::$app->controller->id;
        switch ($actionId) {
            case 'best' : {
                $category = Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID');
                break;
            }
            case 'new' : {
                $category = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
                break;
            }
            case 'discount' : {
                $category = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
                break;
            }
            case 'search' : {
                break;
            }
            default: {

                break;
            }
        }

        $key = "KEY_{$category}_FILTER_CAT_MAN";
        $key_query_result = "KEY_{$category}_FILTER_CAT_MAN_RESULT";

        $time = Setting::get('CACHE.FILTER.CAT.MAN');
        $cache = Yii::$app->cache;
        $params = \Yii::$app->request->queryParams;
        $searchString = $params['ProductSearch']['search'] ?? null;

        if ($category) {
            $data = $cache->getOrSet($key, function () use ($category) {
                return $manufacturers = ProductToCategory::find()
                    ->select('manufacturer.id')
                    ->joinWith('product', false)
                    ->joinWith('product.manufacturer', false)
                    ->where(['product_to_category.categoryId' => $category])
                    ->andWhere('manufacturer.id IS NOT NULL')
                    ->groupBy('manufacturer.id')
                    ->column();
            }, $time);

            $this->_manufacturers = $cache->getOrSet($key_query_result, function () use ($data) {
                return Manufacturer::findAll($data);
            });
        } else {
            $this->_manufacturers = $cache->getOrSet($key_query_result . '_' . $searchString, function () use ($searchString) {
                $query = Manufacturer::find()
                    ->joinWith('products', true, 'RIGHT JOIN')
                    ->andWhere('manufacturer.id IS NOT NULL')
                    ->groupBy('id');
                $this->prepareSearch($query, $searchString);
                return $query->all();
            });
        }

        $nullManuf = new \stdClass();
        $nullManuf->id = 0;
        $nullManuf->title = 'Н/Д';
        $this->_manufacturers[] = $nullManuf;
    }

    /**
     * Подготовка данных к фильтрации по "Наличию".
     */
    public function prepareBalanceFilter()
    {

    }

    /**
     * Подготовка категорий
     */
    public function prepareCategories()
    {
        $category = $this->_category->id ?? null;
        $key = "KEY_{$category}_FILTER_CAT_CAT";
        $time = Setting::get('CACHE.FILTER.CAT.MAN');
        $params = \Yii::$app->request->queryParams;
        $searchString = $params['ProductSearch']['search'] ?? null;
        $controllerId = \Yii::$app->controller->id;
//        dump($controllerId);
        $cache = Yii::$app->cache;
        $this->_categories = $cache->getOrSet($key . '_' . $searchString . '_' . $controllerId, function () use ($category) {
            return Yii::$app->registry->getSqlCategories(false);
        }, $time);
    }

    public function prepareCityFilter()
    {
        $time = Setting::get('CACHE.FILTER.CAT.MAN');
        $cache = Yii::$app->cache;

        $this->_cities = $cache->getOrSet('CACHE_CITY_FILTER', function () {
            return CityOnSite::find()
                ->joinWith('city')
                ->where(['<>', 'city.title', 'Другой город'])
                ->groupBy('cityId')
                ->all();
        });
    }

    public function prepareUniqueLists()
    {

    }

    /**
     * Подготовка категорий и производителей под результат поиска
     * @param ActiveQuery $query
     * @param $search
     */
    public function prepareSearch(ActiveQuery &$query, $search)
    {
        $productSearch = new ProductSearch();
        $productSearch->prepareSearch($query, $search);
    }
}