<?php

namespace app\widgets\ProductListWidget;

use app\components\ClientComponent;
use app\helpers\StringHelper;
use app\models\db\OrderContent;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\ProductToCategory;
use app\models\db\Setting;
use app\models\db\StorageBalance;
use app\system\template\TemplateLoader;
use Yii;
use yii\base\Exception;
//use yii\base\Widget;
use app\system\base\Widget;
use app\modules\catalog\widgets\FilterWidget\models\ProductSearch;
use yii\data\ActiveDataProvider;
use app\models\db\ProductPriceGroup;
use yii\data\Pagination;
use yii\data\Sort;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use app\system\template\TemplateStore;

/**
 * Виджет списка товаров
 *
 *
 * Есть возможность загрузить массив экземпляров класса продукта($models)
 * Есть возможность загрузить кастомный dataProvider/searchModel
 * Два этих способа взаимоисключают друг друга
 *
 * В случае с dataProvider, то возможностей чуть больше
 * Например по-умолчанию дана возможность отображать товары из каталога
 *
 */
class ProductListWidget extends Widget
{
    /** Товары */
    const MODE_DEFAULT = 'default';

    /** Лучшие предложения */
    const MODE_BEST = 'best';

    /** Новинки */
    const MODE_NEW = 'new';

    /** Акции */
    const MODE_DISCOUNTS = 'discount';

    /** Избранное */
    const MODE_FAVORITE = 'favorite';

    /** Поиск */
    const MODE_SEARCH = 'search';

    /** Для главной */
    const MODE_INDEX = 'main';
    const MODE_VIEW_CARD = 'card';
    const MODE_VIEW_LIST = 'list';

    /**
     * Вьюха-элемент смены вида у списка
     * TODO Временно(?) убрано
     */
    const ADDITION_VIEW_CHANGE_VIEW = 'changeView';
    const ADDITION_VIEW_FILTER = 'filter';

    /**
     * Выбранный режим
     * @var string
     */
    public $activeViewMode = self::MODE_VIEW_CARD;

    /** @var array режимы отображения */
    public static $viewModes = [
        self::MODE_VIEW_CARD => 'Режим отображения карточек',
        self::MODE_VIEW_LIST => 'Режим отображения списком'
    ];

    /** @var array список режимов работы списка продуктов */
    public static $modes = [
        self::MODE_DEFAULT => 'default',
        self::MODE_BEST => 'best',
        self::MODE_NEW => 'new',
        self::MODE_DISCOUNTS => 'discount',
        self::MODE_FAVORITE => 'favorite',
        self::MODE_INDEX => 'main',
        self::MODE_SEARCH => 'search'
    ];

    /**
     * Массив классов для каждого режима работы
     * Вынесу каждый режим работы в отдельный класс,
     * но это чуть позже.
     * Надеюсь будет время. :) (К.Ш. 03.04.18)
     * @var array
     * TODO Пока не используется и возможно уже не понадобиться реализовывать
     */
    public static $searchByMode = [
        self::MODE_DEFAULT => 'app\widgets\ProductListWidget\modes\ProductSearchDefault',
        self::MODE_BEST => 'app\widgets\ProductListWidget\modes\ProductSearchBest',
        self::MODE_NEW => 'app\widgets\ProductListWidget\modes\ProductSearchNew',
        self::MODE_DISCOUNTS => 'app\widgets\ProductListWidget\modes\ProductSearchDiscount',
        self::MODE_FAVORITE => 'app\widgets\ProductListWidget\modes\ProductSearchFavorite',
        self::MODE_INDEX => 'app\widgets\ProductListWidget\modes\ProductSearchIndex',
        self::MODE_SEARCH => ''
    ];

    /**
     * @var integer id-списка
     */
    public $id = null;

    /**
     * @var bool|string
     */
    public $title = false;

    /**
     * @var integer количество элементов на страницу
     */
    public $perPage = 36;

    /**
     * @var bool включена ли фильтрация
     */
    public $enableFilter = false;

    /**
     * @var bool включена ли пагинация
     */
    public $enablePagination = true;

    /**
     *
     */
    public $isTurbo = true;

    /** @var array массив настроек */
    public $listOptions = [
        'card' => [
            'itemClass' => 'col-lg-12 col-md-16 col-sm-16'
        ],
        'list' => [
            'itemClass' => 'col-lg-48'
        ],
        'viewElements' => [
            'header' => '',
            'footer' => ''
        ]
    ];

    /** @var array массив настройек карточки в режиме кард */
    public $cardItemOptions = [
        'class' => ''
    ];

    /** @var array массив настроек карточки в режиме списка */
    public $listItemOptions = [
        'class' => ''
    ];

    /**
     * @var ProductSearch загруженная модель поиска
     * @deprecated
     */
    private $searchModel = null;

    /**
     * @var ActiveDataProvider загруженный извне DataProvider
     * @deprecated
     */
    private $dataProvider = null;

    /**
     * Режим работы
     * @var int
     */
    public $mode = self::MODE_DEFAULT;

    /**
     * @var Product[] массив продуктов
     * Данный вариант отключает вариант с dataProvider и searchModel
     */
    public $models = false;

    /** @var array включенные дополнительные вьюхи */
    public $additionViews = [];

    /**
     * Инициализация
     * @throws Exception
     */

    /**
     * @var string Разделяем кеш
     */
    public $cacheIdent = "";

    public function init()
    {
        $cookies = Yii::$app->request->cookies;
        $mode = $cookies->getValue('catalogView');
        $viewModeWidgetId = $cookies->getValue('catalogViewWidgetId');
        $defaultModeName = Setting::get('PRODUCT.LIST.DEFAULT.VIEW');
        if (isset(self::$viewModes[$mode]) && $this->id == $viewModeWidgetId) {
            $this->activeViewMode = $mode;
        } else {
            if (isset(self::$viewModes[$defaultModeName])) {
                $this->activeViewMode = $defaultModeName;
            } else {
                $this->activeViewMode = self::MODE_VIEW_CARD;
            }
        }

        if (!$this->id) {
            throw new Exception('Отсутствие идентификатора виджета');
        }
    }

    /** Запускатор
     * @return bool|mixed|string
     * @throws Exception
     */
    public function run()
    {
        $view = $this->getView();
        ProductListAsset::register($view);
        $queryParams = Yii::$app->request->queryParams;
        $this->perPage = Yii::$app->request->get('per-page') ?? $this->perPage ?? null;

        if ($this->models) {
            $this->isModel();
        } else {
            $this->isProvider();
        }

        if (method_exists($this->searchModel, 'prepareMinMax') && !isset($queryParams['ProductSearch']['search'])) {
            $minMax = $this->searchModel->prepareMinMax() ?? ['min' => 0, 'max' => 500000];
//            $minMax = ['min' => 0, 'max' => 500000];
        } else {
            $minMax = ['min' => 0, 'max' => 500000];
        }

        /** @var ClientComponent $clientComponent */
        $clientComponent = \Yii::$app->client;

        //Временное кеширование меню. Готовимся к четвергу . Павел К. 04.04.2018  16:02
        $cache = \Yii::$app->cache;
        $key = self::class . "run" . md5($this->cacheIdent . $this->mode . (string)$clientComponent->isEntity() . json_encode($_REQUEST));
        if (Setting::get('SITE.CACHE.ENABLE')) {
            $data = $cache->get($key);
        } else {
            $data = false;
        }

        if (\Yii::$app->controller->id == 'search'){
            $data = false;
        }
//        dump($this->dataProvider->query->createCommand()->getRawSql());
        if ($data === false) {
            $data = $this->render('index',
                [
                    'id' => $this->id,
                    'activeViewMode' => $this->activeViewMode,
                    'dataProvider' => $this->dataProvider,
                    //'searchModel' => $this->searchModel,
                    'listOptions' => $this->listOptions,
                    'enableFilter' => $this->enableFilter,
                    'enablePagination' => $this->enablePagination,
                    'title' => $this->title,
                    'mode' => $this->mode,
                    'minMax' => $minMax,
                    'isTurbo' => $this->isTurbo
                ]);
            $cache->set($key, $data, Setting::get('SITE.CACHE.DURATION') * 60); //На 2 часа
        }


        return $data;
    }

    public function prepareMinMax()
    {

    }

    /**
     * Вывод определённых данных в зависимости от режима работы
     */
    public function checkMode()
    {
        /** @var ActiveQuery $query */
        $query = &$this->dataProvider->query;
        $query->with('balances');
        $query->with('certificates');
        $query->with('image');
        $query->with('unit');
        $query->with('reviews');
//        $query->with('manufacturer');

        //todo новое
//        $searchClass = self::$searchByMode[$this->mode];
//        /** @var \app\widgets\ProductListWidget\base\ProductSearch $searchModel */
//        $searchModel = new $searchClass();
//        dump($searchModel->getActiveDataProvider());


        switch ($this->mode) {
            case self::MODE_BEST: {
//                $minCount = 3; // минимальное кол-во проданного товара, для отображения его в хитах
//
//                $queryBestSeller = (new Query())
//                    ->select('productId')
//                    ->from('order_content')
//                    ->groupBy('productId')
//                    ->having("sum(count) > $minCount")
//                    ->orderBy("sum(count) DESC");
//
//                $query
//                    ->andWhere(['product.id' => $queryBestSeller]);

                $query
                    ->innerJoin('product_to_category ptcbest', 'ptcbest.productId = product.id')
                    ->andWhere(['ptcbest.categoryId' => Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID')]);

                break;
            }
            case self::MODE_NEW: {
                $query
//                    ->joinWith('categories')
                    ->innerJoin('product_to_category ptcnew', 'ptcnew.productId = product.id')
                    ->andWhere(['ptcnew.categoryId' => Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID')]);
                break;
            }
            case self::MODE_FAVORITE: {
                $favoriteIds = Yii::$app->favorite->productIds;
                $query
                    ->andWhere(['product.id' => $favoriteIds]);

                break;
            }
            case self::MODE_DISCOUNTS: {
                $query
                    ->innerJoin('product_to_category ptcdiscount', 'ptcdiscount.productId = product.id')
                    ->andWhere(["ptcdiscount.categoryId" => Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID')]);

                break;
            }
            case self::MODE_INDEX: {
                $query
                    ->innerJoin('product_to_category ptcindex', 'ptcindex.productId = product.id')
                    ->andWhere(['ptcindex.categoryId' => Setting::get('PRODUCT.LIST.MAIN.CATEGORY.ID')]);

                break;
            }
            case self::MODE_SEARCH: {

                break;
            }
        }
    }

    /**
     * Если была загружена модель
     * Данный метод пока работает без пагинации
     * @deprecated зря добавил это гавно, переделаю, НЕ ИСПОЛЬЗОВАТЬ!!!
     */
    public function isModel()
    {
        array_splice($this->models, $this->perPage);
        $this->dataProvider = new ActiveDataProvider();
        $this->dataProvider->models = $this->models;
        $this->dataProvider->totalCount = count($this->models);
        $this->dataProvider->pagination = new Pagination();
        $this->dataProvider->pagination->pageSize = 0;
        $this->searchModel = null;
        $this->enablePagination = false;
    }

    /**
     * Если был загружен activeProvider
     * @deprecated зря добавил это гавно, переделаю, НЕ ИСПОЛЬЗОВАТЬ!!!
     */
    public function isProvider()
    {
        $pageSize = $this->perPage ?? 36;
        $searchModel = $this->searchModel ?? new ProductSearch();
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $this->dataProvider ?? $searchModel->search(Yii::$app->request->queryParams);

        $queryParams = Yii::$app->request->queryParams;
//

		// убираем лишние пробелы...
		if (isset($queryParams['ProductSearch']['search']) && isset($dataProvider->models[0])) {
			$title = preg_replace('/[\s]{2,}/u', ' ', $dataProvider->models[0]->title);
		}

        if (isset($queryParams['ProductSearch']['search']) && $dataProvider->totalCount == 1
				&& ($title == $queryParams['ProductSearch']['search']
				|| $dataProvider->models[0]->vendorCode == $queryParams['ProductSearch']['search']
				|| $dataProvider->models[0]->backCode == $queryParams['ProductSearch']['search'])) {
            Yii::$app->response->redirect("/product/{$dataProvider->models[0]->slug}");
        }

//        if (Yii::$app->setting->get('PRODUCT.SHOW.NULL.BALANCE')) {
//            $dataProvider->query->innerJoin(['sb' => StorageBalance::tableName()], 'sb.productId = product.id');
//            $dataProvider->query->andWhere(
//                [
//                    'AND',
//                    ['>', 'sb.quantity', 0],
//                    [
//                        'state' => StorageBalance::STATE_IN_STOCK
//                    ]
//                ]
//            );
//        }

        $dataProvider->query->andWhere(['product.status' => Product::STATUS_PUBLISHED]);

        $dataProvider->pagination->pageSize = $pageSize;
        $dataProvider->query->limit($pageSize);
//        $dataProvider->key = 'slug';
        $this->dataProvider = $dataProvider;
        $this->searchModel = $searchModel;
        $this->checkMode();
        TemplateStore::setVar("PRODUCT.LIST.COUNT", $dataProvider->totalCount . ' ' . StringHelper::decline($dataProvider->totalCount));
    }
}