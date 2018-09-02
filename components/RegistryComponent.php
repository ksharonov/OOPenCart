<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.04.2018
 * Time: 13:30
 */

namespace app\components;


use app\helpers\ModelRelationHelper;
use app\models\db\OuterRel;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\Storage;
use app\models\db\Unit;
use app\modules\catalog\widgets\FilterWidget\models\ProductSearch;
use yii\base\Component;
use app\models\db\Setting;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\Expression;

class RegistryComponent extends Component
{
    /** @var Storage[] */
    protected $storages = null;

    /** @var ProductCategory[] */
    protected $sqlCategories = null;

    /** @var Product[] */
    protected $products = null;

    /** @var array */
    protected $storage;

    protected $categoryByController = [];

    public function __construct(array $config = [])
    {
        $this->categoryByController['best'] = Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID');
        $this->categoryByController['new'] = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
        $this->categoryByController['discount'] = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
        parent::__construct($config);
    }

    /** Получение массива складов и магазинов в БД
     * @return Storage[]|array|\yii\db\ActiveRecord[]
     */
    public function getStorages($where = [])
    {
        if (!isset($this->storages)) {
            $this->storages = Storage::find()
                ->where($where)
                ->with('address')
                ->all();;
        }

        return $this->storages;
    }

    /** Все категории и кол-во товаров в них одним запросом
     * @property bool $fullData
     * @return ProductCategory[]|array|\yii\db\ActiveRecord[]
     * @throws \yii\base\Exception
     */
    public function getSqlCategories($fullData = true)
    {
        if (isset($this->sqlCategories) && !$fullData) {
            return $this->sqlCategories;
        }
        // -----------------------------------------------------init

        /** @var ClientComponent $client */
        $client = \Yii::$app->client;

        $showNullBalance = Setting::get('PRODUCT.SHOW.NULL.BALANCE');
        $retail = Setting::get('DEFAULT.PRICE.ID');
        $wholesale = Setting::get('WHOLESALE.PRICE.ID');

        //------------------------------------------------------

        $sqlTotalCountQuery = (new Query())
            ->select('COUNT(*)')
            ->from('product_to_category ptc')
            ->rightJoin("product_price retail", "retail.productId = ptc.productId and retail.productPriceGroupId=$retail")
            ->rightJoin("product_price wholesale", "wholesale.productId = ptc.productId and wholesale.productPriceGroupId = $wholesale")
            ->where('ptc.categoryId = cats.id')
            ->andwhere('retail.value > 0 AND retail.value IS NOT NULL')
            ->andWhere('wholesale.value > 0 AND wholesale.value IS NOT NULL');

        if ($client->isIndividual() && $showNullBalance == false) {
            $summ = (new Query())
                ->select('SUM(quantity)')
                ->from('storage_balance')
                ->where('productId = ptc.productId');
            $summ = $summ->createCommand()->rawSql;
            $sqlTotalCountQuery
                ->andWhere(['>', "(" . $summ . ")", 0]);
        }

        $sqlCats = (new Query())
            ->select([
                'pc1.*',
                'sqlChilds' => new Expression('GROUP_CONCAT(DISTINCT pc.id)')
            ])
            ->from('product_category pc1')
            ->leftJoin('product_category pc', 'pc1.id = pc.parentId')
            ->where(['pc1.status' => ProductCategory::STATUS_PUBLISHED])
            ->groupBy('pc1.id')
            ->createCommand()
            ->rawSql;

        $query = ProductCategory::find()
            ->select([
                'cats.*',
                'sqlTotalCount' => $sqlTotalCountQuery
            ])
            ->from(['cats' => "(" . $sqlCats . ")"])
            ->orderBy('cats.parentId')
            ->groupBy('cats.id')
            ->indexBy('id');

        $params = \Yii::$app->request->queryParams;
        $searchString = $params['ProductSearch']['search'] ?? null;

        if ($searchString && !$fullData) {
            $query->joinWith('products', true, 'LEFT JOIN');
            $this->prepareSearch($query, $searchString);
        }

        $this->sqlCategories = $query->all();

        return $this->sqlCategories;
    }

    /** Массив всех товаров в базе, где ключи - guid */
    public function getProducts()
    {
        $relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
        if ($this->products) {
            return $this->products;
        } else {
            //$this->products = Product::find()->indexBy('guid')->all();
            $this->products = Product::find()
                ->select('product.*')
                ->addSelect('orel.guid as sqlGuid')
                ->leftJoin('outer_rel orel', "orel.relModelId = product.id AND orel.relModel = $relModel")
//                ->limit(100)
                ->indexBy('sqlGuid')
                ->all();
        }

        return $this->products;
    }

    public function getUnits()
    {
        $relModel = ModelRelationHelper::REL_MODEL_UNIT;
        if (!$this->storage['unit']) {
            $this->storage['unit'] = Unit::find()
                ->addSelect('orel.guid as sqlGuid')
                ->leftJoin('outer_rel orel', "orel.relModelId = unit.id AND orel.relModel = $relModel")
                ->indexBy('sqlGuid')
                ->all();
        }

        return $this->storage['unit'];
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