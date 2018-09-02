<?php
namespace app\modules\lexema\controllers;

use app\models\db\Client;
use app\models\db\Order;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\modules\backoffice\models\Lexema;
use app\modules\lexema\api\Connection;
use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\connection\HttpConnection;
use app\modules\lexema\api\CronTask;
use app\modules\lexema\api\Import;
use app\modules\lexema\api\repository\ProductFileRepository;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\Semaphor;
use app\modules\lexema\api\Token;
use app\modules\lexema\cron\CheckNew;
use app\modules\lexema\cron\CheckSales;
use app\modules\lexema\models\LexemaConnect;
use app\modules\lexema\models\LexemaImport;
use app\modules\lexema\models\OrderExport;
use app\modules\lexema\models\Production;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Controller;


class DefaultController extends Controller
{
	/** @var Connection  */
	public $connection = null;

    public function init()
    {
    	//$this->connection = Connection::get();
    }

    public function actionImportall()
    {
        set_time_limit(0);
        $start = time();

		$import = new Import();
//		$import->importProduction();
		$import->test();

		dump(time() - $start);
		dump(memory_get_peak_usage() / 1024 / 1024);
    }

}