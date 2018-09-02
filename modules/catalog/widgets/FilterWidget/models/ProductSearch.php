<?php

namespace app\modules\catalog\widgets\FilterWidget\models;

use app\components\ClientComponent;
use app\helpers\StringHelper;
use app\models\db\Client;
use app\models\db\Product;
use app\models\db\ProductPrice;
use app\models\db\ProductPriceGroup;
use app\models\db\Setting;
use app\models\db\Storage;
use app\models\db\StorageBalance;
use app\system\template\TemplateStore;
use Codeception\Module\Cli;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\Expression;
use yii\db\QueryBuilder;
use yii\helpers\Url;

/**
 * Модель поиска продуктов по фильтру
 */
class ProductSearch extends Product
{
    public $search = null;

    public $sort = null;

    public $category = null;

    /** @var object | array массив кастомных параметров */
    public $customParams = [];

    /**
     * @var array фильтрация цены
     */
    public $priceFilter = [];

    /**
     * Сортировка по наличию
     * @var null
     */
    public $balanceFilter = false;

    /**
     * Сортировка по городам
     * @var null
     */
    public $cityFilter = null;


    /**
     * @var array данные для фильтрации производителя
     */
    public $manufacturerFilter = [];

    public $query = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'content', 'dtUpdate', 'dtCreate', 'sort', 'search', 'category'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /** Создание dataProvider-экземпляра с параметрами запроса
     *
     * @param $params
     *
     * @return ActiveDataProvider
     * @throws \yii\base\Exception
     */
    public function search($params)
    {
        $client = \Yii::$app->client;

        $query = Product::find();
//        $query->groupBy('product.id');
        $query
            ->addSelect('product.*');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (isset($params['ProductSearch']['search'])) {
            $params['search'] = $params['ProductSearch']['search'];
            unset($params['ProductSearch']['search']);
        }

        if (count($params) > 0) {
            $this->load($params);
        }

        if (isset($params['sort'])) {
            $this->sort = $params['sort'];
            unset($params['sort']);
        }

        $keyManufNullFilter = array_search(0, $this->manufacturerFilter);

        if ($keyManufNullFilter !== false) {
            $query->andWhere(['IS', 'manufacturerId', null]);
        } elseif ($this->manufacturerFilter) {
            $query->andWhere(['manufacturerId' => $this->manufacturerFilter]);
        }

        // поиск
        if (isset($params['search']) && $params['search'] != '') {
            return $this->prepareSearchMode($query, $dataProvider, $params);
        }

        $query->andFilterWhere([
            'product.id' => $this->id,
        ]);


        if (count($params) > 0 && !$this->validate()) {
            return $dataProvider;
        };

        if ($this->category !== null && $this->category !== '') {

            $current = $this->category;
            $status  = Product::STATUS_PUBLISHED;

            $query
                ->select([
                    'product.*'
                ])
                ->from('product')
                ->innerJoin('product_to_category', 'product_to_category.productId = product.id')
                ->innerJoin('product_category', 'product_category.id = product_to_category.categoryId');
            if (is_array($this->category)) {
                $query->andWhere(["product_to_category.categoryId" => $current]);
            } elseif (is_int($this->category) || is_string($this->category)) {
                $query->andWhere("product_to_category.categoryId = $current");
            }

            $query->andWhere("product.status = $status");
        }

        //Поиск по атрибутам
        $keyDB = 0;
        foreach ($this->customParams as $key => $param) {
            $query->innerJoin("product_filter_to_category `pftc{$keyDB}`",
                "`pftc{$keyDB}`.categoryId = product_category.id");
            $query->innerJoin("product_filter `pf{$keyDB}`", "`pftc{$keyDB}`.filterId = `pf{$keyDB}`.id");
            $query->innerJoin("product_to_attribute `pta{$keyDB}`", "`pta{$keyDB}`.productId = product.id");
            $query->innerJoin("product_attribute `pa{$keyDB}`", "`pf{$keyDB}`.sourceId = `pa{$keyDB}`.id");
            $query->andWhere(['AND', ["`pa{$keyDB}`.name" => $key], ["`pta{$keyDB}`.attrValue" => $param]]);
            $keyDB++;
        }


        $this->prepareMarks($query);
        $this->preparePrices($query);
        $this->prepareStorages($query);
        $this->prepareManufacturer($query);
        $this->prepareRating($query);
        $this->prepareSort($query);
        $this->prepareUser($query);

        $this->query = new Query();
        $this->query = clone $query;
        // можно вынести в отдельный метод preparePriceFilter


        if ($client->isIndividual()) {
            $mainPrice = 'retail';
        } else {
            $mainPrice = 'wholesale';
        }

        if ($this->priceFilter) {
            list($from, $to) = $this->priceFilter;

            $query->andWhere(['between', "$mainPrice.value", $from, $to]);
        }
        //todo Проблема join-а одновременно с атрибутами и опциям, но фильтрация по опциям пока не планируется


        if ($keyManufNullFilter !== false) {
            $query->andWhere(['IS', 'manufacturerId', null]);
        } elseif ($this->manufacturerFilter) {
            $query->andWhere(['manufacturerId' => $this->manufacturerFilter]);
        }

        return $dataProvider;
    }

    /**
     * Немного доделал загрузку данных фильтров
     */
    public function load($data, $formName = null)
    {
        $dataArr = $data[$this->formName()] ?? [];
        foreach ($dataArr as $key => $param) {
            $this->customParams[$key] = $param;
        }

        if (!($this->customParams instanceof \stdClass) && isset($this->customParams['sort'])) {
            $this->sort = $this->customParams['sort'];
            unset($this->customParams['sort']);
        }

        if (!($this->customParams instanceof \stdClass) && isset($this->customParams['category'])) {
            unset($this->customParams['category']);
        }

        $this->customParams = (object)$this->customParams;

        $this->prepareSearchPrice();
        $this->prepareSearchManufacturer();
        $this->prepareSearchBalance();
        $this->prepareSearchCity();

        return parent::load($data, $formName);
    }

    /**
     * Инициализация фильтрации цен
     */
    public function prepareSearchPrice()
    {
        if (isset($this->customParams->priceFilter)) {
            $this->priceFilter = $this->customParams->priceFilter;
            unset($this->customParams->priceFilter);
        }
    }

    /**
     * Инициализация фильтрации производителей
     */
    public function prepareSearchManufacturer()
    {
        if (isset($this->customParams->manufacturerFilter)) {
            $this->manufacturerFilter = $this->customParams->manufacturerFilter;
            unset($this->customParams->manufacturerFilter);
        }
    }

    /**
     * Поиск по наличию
     */
    public function prepareSearchBalance()
    {
        if (isset($this->customParams->balanceFilter)) {
            $this->balanceFilter = (boolean)$this->customParams->balanceFilter;
            unset($this->customParams->balanceFilter);
        }
    }

    /**
     * Поиск по городам
     */
    public function prepareSearchCity()
    {
        if (isset($this->customParams->cityFilter)) {
            $this->cityFilter = $this->customParams->cityFilter;
            unset($this->customParams->cityFilter);
        }
    }

    /**
     * @param ActiveQuery $query
     * @param ActiveDataProvider $dataProvider
     * @param $params
     *
     * @return mixed
     */
    public function prepareSearchMode(ActiveQuery &$query, &$dataProvider, $params)
    {
        $this->preparePrices($query);
        $this->prepareStorages($query);
        $this->prepareManufacturer($query);
        $this->prepareSort($query);
        $this->prepareMarks($query);
        $this->prepareRating($query);

        $searchString = $params['search'];
        $this->prepareSearch($query, $searchString);

        // ================= сортировка
        if (!$this->sort || $this->sort == ' id') {
            $query->orderBy('sqlIsSale DESC, sqlIsNew DESC, sqlIsBest DESC');

            $balanceQuery = (new Query())
                ->select([new Expression("IF(SUM(quantity)>0,1,0)")])
                ->from('storage_balance')
                ->where('productId = product.id');
            $query->addOrderBy('`id` ASC');
            $query->addOrderBy(new Expression("(" . $balanceQuery->createCommand()->rawSql . ") DESC"));

            /** @var Client $client */
            $client = \Yii::$app->client;

            if ($client->isIndividual()) {
                $query->addOrderBy("retail.value ASC");
            } else {
                $query->addOrderBy("wholesale.value ASC");
            }
        }
        // ==============


        if ($this->category !== null && $this->category !== '') {
            $current = $this->category;
            $query->innerJoin('product_to_category', 'product_to_category.productId = product.id');
            $query->andWhere(["product_to_category.categoryId" => $current]);
        }

        if ($client->isIndividual()) {
            $mainPrice = 'retail';
        } else {
            $mainPrice = 'wholesale';
        }

        if ($this->priceFilter) {
            list($from, $to) = $this->priceFilter;

            $query->andWhere(['between', "$mainPrice.value", $from, $to]);
        }
        $query->addGroupBy('`id`');
        $this->query = $query;

//        dump($query->createCommand()->getRawSql());
        return $dataProvider;
    }

    /** Подготовка запроса под цены
     *
     * @param ActiveQuery $query
     *
     * @throws \yii\base\Exception
     */
    public function preparePrices(ActiveQuery &$query)
    {
        /* Это под функционал, если у клиента есть своя цена */
        /** @var ClientComponent $client */
        $client = \Yii::$app->client;

        $retail = Setting::get('DEFAULT.PRICE.ID');

        if (isset($client->client->priceGroup) && ($client->client->priceGroup->id != $retail)) {
            $wholesale = $client->priceGroup->id;
        } else {
            $wholesale = Setting::get('WHOLESALE.PRICE.ID');
        }

        $query->addSelect(['sqlPrices' => 'JSON_OBJECT("retail", retail.value, "wholesale", wholesale.value)']);

        if ($client->isEntity()) {
            $query->leftJoin("product_price retail",
                "retail.productId = product.id and retail.productPriceGroupId=$retail");
            $query->rightJoin("product_price wholesale",
                "wholesale.productId = product.id and wholesale.productPriceGroupId = $wholesale");
            $query->andWhere('wholesale.value > 0 AND wholesale.value IS NOT NULL');
        } else {
            $query->leftJoin("product_price wholesale",
                "wholesale.productId = product.id and wholesale.productPriceGroupId = $wholesale");
            $query->rightJoin("product_price retail",
                "retail.productId = product.id and retail.productPriceGroupId=$retail");
            $query->andWhere('retail.value > 0 AND retail.value IS NOT NULL');
        }
    }

    /** Подготовка запроса под остатки товаров на складах
     *
     * @param ActiveQuery $query
     *
     * @throws \Exception
     */
    public function prepareStorages(ActiveQuery &$query)
    {
        /** @var ClientComponent $client */
        $client = \Yii::$app->client;

        /** @var Storage[] $storages */

        if ((bool)$this->cityFilter) {
            $storageWhere = ['cityId' => $this->cityFilter];
        } else {
            $storageWhere = [];
        }

        $storages = \Yii::$app->registry->getStorages($storageWhere, true);

        $showNullBalance = Setting::get('PRODUCT.SHOW.NULL.BALANCE') || $this->balanceFilter;

        if (!$storages) {
            throw new \Exception("Нет складов в БД.");
        }

        $string    = '';
        $orStorage = [];

        foreach ($storages as $storage) {
            $id     = $storage->id;
            $string .= "\"$id\", sb{$id}.quantity,";
            $query->leftJoin("storage_balance sb{$id}", "sb{$id}.productId = product.id and sb{$id}.storageId = $id");

            if ((bool)$this->cityFilter) {
                $query->leftJoin("storage s{$id}", "s{$id}.id = sb{$id}.storageId");
                $orStorage[] = ["s{$id}.cityId" => $this->cityFilter];
            }
        }

        $jsonStorageList = trim($string, ",");

        if ((bool)$this->cityFilter) {
            $query->andWhere(array_merge(['OR'], $orStorage));
        }
//        exit;
//        $query->andWhere(['OR', $stQuery]);
        $query->addSelect(['sqlStorages' => "JSON_OBJECT($jsonStorageList, \"summ\", (SELECT SUM(quantity) FROM storage_balance WHERE productId = product.id))"]);
//        $query->andWhere(['sqlStorages' => 1]);
        if ($showNullBalance) { //$client->isIndividual() && $showNullBalance == false
            $summ = (new Query())
                ->select('SUM(quantity)')
                ->from('storage_balance')
                ->where('productId = product.id');
            $summ = $summ->createCommand()->rawSql;
//            dump($summ);
            $query
                ->andWhere(['>', "(" . $summ . ")", 0]);

            /**
             * **pp - Продукция поставщиков (отображение товаров в списке, если даже их нет в наличии)
             */
            $query
                ->innerJoin('product_to_category ptcpp', 'ptcpp.productId = product.id')
                ->innerJoin('product_category pcpp', 'pcpp.id = ptcpp.categoryId')
                ->orWhere(['OR', 'pcpp.title = "Продукция поставщиков"', ['pcpp.id' => $this->category]]);
        }
    }

    /** Подготовка запроса под производителей
     *
     * @param ActiveQuery $query
     */
    public function prepareManufacturer(ActiveQuery &$query)
    {
        $query
            ->addSelect(['sqlManufacturer' => 'manufacturer.title'])
            ->leftJoin('manufacturer', 'manufacturer.id = product.manufacturerId');
    }

    /**
     * Чуть позже подумать над универсальным решением
     * Стандартное решение есть, но оно не подходит сюда
     *
     * @param ActiveQuery $query
     */
    public function prepareSort(ActiveQuery &$query)
    {
        $client = \Yii::$app->client;
        // std sort
        if ($this->category && !$this->sort) {
            $query->orderBy('sqlIsSale DESC, sqlIsNew DESC, sqlIsBest DESC');
        }

        if (strpos($this->sort, '-') !== false) {
            $type = 'DESC';
        } else {
            $type = 'ASC';
        }

        if ($this->sort && strpos($this->sort, 'title') !== false) {
            $query->orderBy("product.title $type");
        }

        if ($this->sort && strpos($this->sort, 'price') !== false) {
            if ($client->isIndividual()) {
                $query->orderBy("retail.value $type");
            } else {
                $query->orderBy("wholesale.value $type");
            }
        }
    }

    /**
     * Подготовка запроса под плашки ("Новинка", "Хит", "Акция")
     *
     * @param ActiveQuery $query
     *
     * @throws \yii\base\Exception
     */
    public function prepareMarks(ActiveQuery &$query)
    {
        $discountId = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
        $newId      = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
        $bestId     = Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID');

        $queryDiscount = (new Query())
            ->select(new Expression('id>0'))
            ->from('product_to_category')
            ->where('product_to_category.productId = product.id')
            ->andWhere("product_to_category.categoryId = $discountId")
            ->limit(1);

        $queryBest = (new Query())
            ->select(new Expression('id>0'))
            ->from('product_to_category')
            ->where('product_to_category.productId = product.id')
            ->andWhere("product_to_category.categoryId = $bestId")
            ->limit(1);

        $queryNew = (new Query())
            ->select(new Expression('id>0'))
            ->from('product_to_category')
            ->where('product_to_category.productId = product.id')
            ->andWhere("product_to_category.categoryId = $newId")
            ->limit(1);

        $query
            ->addSelect([
                'sqlIsSale' => $queryDiscount,
                'sqlIsNew'  => $queryNew,
                'sqlIsBest' => $queryBest,
            ]);
    }

    /**
     * Подзапрос рейтинга
     *
     * @param ActiveQuery $query
     */
    public function prepareRating(ActiveQuery &$query)
    {
        $averageRating = (new Query())
            ->select(new Expression('AVG(rating)'))
            ->from('product_review')
            ->where(['status' => 1])
            ->andWhere('productId = product.id');

        $query->addSelect(['sqlRating' => $averageRating]);
    }

    /**
     * Подготовка поиска
     *
     * @param ActiveQuery $query
     * @param $searchString
     */
    public function prepareSearch(ActiveQuery &$query, $searchString)
    {
        $this->prepareAdditionsSearch($searchString);
        $searchString = trim(str_replace('%', '', $searchString));

        if ($searchString == "") {
            return;
        }
        $fulltext = $this->prepareFulltext($searchString);

        $like       = $searchString; //mb_ereg_replace('[^a-zA-Zа-яА-Я0-9 ,"\'./-]', '', $searchString);
        $vendorCode = mb_ereg_replace('[^a-zA-Zа-яА-Я0-9 -/.,]', '', $searchString);

        $query->andWhere([
            'OR',
            "MATCH (product.title) AGAINST ('$fulltext' IN BOOLEAN MODE)",
            "product.title LIKE '%$like%'",
            "product.vendorCode like '%$vendorCode%'",
        ]);

//		$query->orWhere("product.title LIKE '%$like%'");
//		$query->orWhere(['product.vendorCode' => $vendorCode]);
        if (is_numeric($like)) {
            $query->orWhere(['product.backCode' => (int)$like]);
        }

        if ($query->count() == 0) {
            $inverted = StringHelper::invertKeyLayout($searchString);

            $fulltext = $this->prepareFulltext($inverted);
            $like     = $inverted; //mb_ereg_replace('[^a-zA-Zа-яА-Я0-9 ,"\'./-]', '', $inverted);


            $query->orWhere("MATCH (product.title) AGAINST ('$fulltext' IN BOOLEAN MODE)");
            $query->orWhere("product.title LIKE '%$like%'");
        }

    }

    /**
     * Худшая реализация по замене светодиода на led
     *
     * @param $searchString
     */
    protected function prepareAdditionsSearch(&$searchString)
    {
        $needleArrayLed = ['ветодиод', 'диод', 'свето', 'ветодиод', 'диот'];
        if ($this->strpos_arr($searchString, $needleArrayLed) !== false) {
            $searchString = 'led';
        }
    }

    /**
     * Нашлось ли в строке значение из массива
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool|int
     */
    public function strpos_arr($haystack, $needle)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $what) {
            if (($pos = strpos($haystack, $what)) !== false) {
                return $pos;
            }
        }

        return false;
    }

    /**
     * Подготовка запроса для полнотекстового поиска
     *
     * @param $searchString
     *
     * @return array|false|string
     */
    protected function prepareFulltext($searchString)
    {
        $fulltext = mb_ereg_replace('[^a-zA-Zа-яА-Я0-9]', ' ', $searchString);
        $fulltext = trim($fulltext);

        if ($fulltext == "") {
            return "";
        }

        $fulltext = mb_ereg_replace(" {2,}", " ", $fulltext);
        $fulltext = explode(" ", $fulltext);

        foreach ($fulltext as &$string) {
            $string = '+' . $string . '*';
        }

        $fulltext = implode('', $fulltext);

        return $fulltext;
    }

    /**
     * @param ActiveQuery $query
     *
     * @return string
     */
    public function prepareUser(ActiveQuery &$query)
    {
        return "HREN";
        /** @var ClientComponent $client */
        $client = \Yii::$app->client;

        if ($client->isIndividual()) {
            /*
             * Подумать: оставить подзапрос или юзать join?
             */
            $countQuery = StorageBalance::find()
                                        ->select('productId, sum(quantity) as quantitySum')
                                        ->groupBy('productId')
                                        ->indexBy('productId')
                                        ->having('quantitySum > 0')
                                        ->asArray()
                                        ->column();
            $arrResult  = array_keys($countQuery);

            $query
                ->andWhere(['product.id' => $arrResult]);
        }
    }

    public function preparePriceFilter()
    {

    }

    /** Установка макс/мин значений при выборе ценового диапазона
     * @return array
     */
    public function prepareMinMax($query = null)
    {
        if (!$query) {
            $query = $this->query;
        }

        if ($query->count() == 0) {
            return;
        }

        $client = \Yii::$app->client;

        if ($client->isIndividual()) {
            $pg = "retail";
        } else {
            $pg = "wholesale";
        }

        $array = [
            'min' => $query->min("$pg.value"),
            'max' => $query->max("$pg.value"),
        ];

        //dump($array);

        return $array;
    }

}