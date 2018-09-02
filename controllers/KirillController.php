<?php

namespace app\controllers;

use app\components\RegistryComponent;
use app\helpers\ChequeHelper;
use app\models\db\Client;
use app\models\db\Order;
use app\models\db\OrderContent;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\ProductPriceGroup;
use app\models\db\ProductToCategory;
use app\models\db\StorageBalance;
use app\models\session\CitySession;
use app\models\session\OrderSession;
use app\system\db\ActiveRecordCookie;
use app\system\db\ActiveRecordSession;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\web\Controller;
use NumberFormatter;
use app\models\db\Setting;

class KirillControllerDel extends Controller
{
    public function actionIndex()
    {
        $client = Client::findOne(1);

        $ppg = ProductPriceGroup::findPriceGroupByClient($client);
        dump($ppg);
    }

    public function actionTest()
    {
        $ok = setcookie('test', 'test', time() + 3600 * 24 * 7, '/');
        dump($ok);
        dump($_COOKIE);
        dump($_COOKIE);
    }

    public function actionOk()
    {
        $queryBest = OrderContent::find()
            ->select('productId, count(*) as count, sum(count) as sum')
            ->orderBy('count DESC')
            ->groupBy('productId')
//            ->indexBy('productId')
            ->having('count > 100')
            ->asArray()->all();
//            ->column();

        dump($queryBest);

    }

    public function actionOk2()
    {
        $start_time = microtime(true);
        $countQuery = [];


        $countQuery = StorageBalance::find()
            ->select('productId, sum(quantity) as quantitySum')
            ->groupBy('productId')
            ->indexBy('productId')
            ->having('quantitySum > 0')
            ->asArray()
            ->column();
        $arrResult = array_keys($countQuery);

        $end_time = microtime(true);
        $time = $end_time - $start_time;
        dump($time);
        dump($arrResult);
    }

    public function actionOk3()
    {
        $number = 0;//1011111.555
        $fmt = new NumberFormatter('ru_RU', NumberFormatter::CURRENCY);
        $fmt->setPattern('#,##0.00'); // #,##0.00 ¤
        $moneyValue = $fmt->formatCurrency($number, 'RUR');
        var_dump($moneyValue);
    }

    public function actionOk4()
    {
        $start_time = microtime(true);
        $ok = ProductToCategory::find()
            ->select('manufacturer.title')
            ->joinWith('product', false)
            ->joinWith('product.manufacturer', false)
            ->where(['categoryId' => 12])
            ->groupBy('manufacturer.id')
            ->column();
        $end_time = microtime(true);
        $time = $end_time - $start_time;
        dump($time);
        dump($ok);
    }

    public function actionSqlTest()
    {
        // -----------------------------------------------------init

        $showNullBalance = Setting::get('PRODUCT.SHOW.NULL.BALANCE');

        /** @var Storage[] $storages */
        $storages = \Yii::$app->registry->storages;

        $retail = Setting::get('DEFAULT.PRICE.ID');
        $wholesale = Setting::get('WHOLESALE.PRICE.ID');

        //------------------------------------------------------


        //--------------------------------------------------------

        $sqlCategoriesQuery = (new Query())
            ->select('pc.id')
            ->from('product_category pc1')
            ->leftJoin('product_category pc', 'pc1.id = pc.parentId')
            ->where([
                'or',
                'pc1.id = cats.id',
                'pc.id = cats.id'
            ])
            ->createCommand()
            ->rawSql;

        $retail = Setting::get('DEFAULT.PRICE.ID');
        $wholesale = Setting::get('WHOLESALE.PRICE.ID');

        $sqlTotalCountQuery = (new Query())
            ->select('COUNT(*)')
            ->from('product_to_category ptc')
            ->rightJoin("product_price retail", "retail.productId = ptc.productId and retail.productPriceGroupId=$retail")
            ->rightJoin("product_price wholesale", "wholesale.productId = ptc.productId and wholesale.productPriceGroupId = $wholesale")
            ->where('ptc.categoryId = cats.id')
            ->andwhere('retail.value > 0 AND retail.value IS NOT NULL')
            ->andWhere('wholesale.value > 0 AND wholesale.value IS NOT NULL');
        if ($showNullBalance == false) {
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

        $cats = $query->all();
        dump($cats);

    }

    public function actionOk5()
    {
        $model = Product::findByGuid(148811);
        $model->title = 1;
        $model->save(false);
        dump([
            $model->relModel,
            $model::getRelModel(),
            $model->guid,
            $model
        ]);
    }

    public function actionOk6()
    {
        $ok = new Product();
//        $ok->save();
        $ok->refresh();
        dump($ok);
        $ok2 = Product::findOne(13);
        dump($ok2);
    }

    public function actionOk7()
    {
        $order = Order::findOne(1);
        $order->finalSum;

        $cheque = new ChequeHelper();
//        $cheque->printString('11', '111');
//        $cheque->request('openTurn');
        $cheque->printOrder();
    }
}