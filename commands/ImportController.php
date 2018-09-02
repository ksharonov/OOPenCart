<?php

namespace app\commands;

use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\Setting;
use app\modules\lexema\api\Import;
use app\modules\lexema\api\Semaphor;
use app\modules\lexema\api\CronTask;
use app\modules\lexema\models\LexemaImport;
use yii\console\Controller;

class ImportController extends Controller {

    /** @var LexemaImport */
    private $importer;

    // TODO оставил пока старый импорт для некоторых вещей он нужен
    public function init() {
        parent::init();

//        $connection = new LexemaConnect();
        $this->importer = new LexemaImport();
        error_reporting(E_ALL);
    }

    // Старое НЕ ИСПОЛЬЗОВАТЬ!
    /*
      public function actionAll()
      {
      $this->importer->importUnit();
      $this->importer->importCategory();
      $this->importer->importProducts();
      $this->importer->importPriceGroup();
      $this->importer->importPrice();
      $this->importer->importFile();
      $this->importer->importAnalogue();
      $this->importer->importAssociated();
      $this->importer->importStorage();
      $this->importer->importStorageBalance();
      $this->importer->importUser();
      $this->importer->importClient();
      $this->importer->importContract();
      }

      public function actionUnit()
      {
      $this->importer->importUnit(); // готово
      }

      public function actionProducts()
      {
      $this->importer->importProducts(); // готово
      }

      public function actionProduct($guid)
      {
      $this->importer->importProduct($guid);
      }

      public function actionSlug()
      {
      $this->importer->updateSlug();
      }

      public function actionCategory()
      {
      $this->importer->importCategory();
      }

      public  function actionPriceGroup()
      {
      $this->importer->importPriceGroup(); // готово
      }

      public function actionPrice()
      {
      //$this->importer->importPrice(); // готово
      $this->importer->importPrice();
      }

      public function actionFile($mode = "all", $from = 0)
      {
      $this->importer->importFile($mode, $from);  // готово
      }

      public function actionFileByGuid($guid)
      {
      $this->importer->importFileByGuid($guid);
      }

      public function actionAnalogue()
      {
      $this->importer->importAnalogue(); // готово
      }

      public function actionAssociated()
      {
      $this->importer->importAssociated(); // готово
      }

      public function actionStorage()
      {
      $this->importer->importStorage(); // готово
      }

      public function actionShop()
      {
      $this->importer->importShop();
      $this->importer->importStorage();
      }

      public function actionStorageBalance()
      {
      $this->importer->importStorageBalance(); // готово
      //        $this->importer->importBalanceByGuid('cb52734a-6ee5-11e3-8b80-008048319a51');
      }

      public function actionUser()
      {
      $this->importer->importUser(); // готово
      }

      public function actionClient()
      {
      $this->importer->importClient(); // готово
      }

      public function actionContract()
      {
      $this->importer->importContract();
      }

      public function actionOrder()
      {
      $this->importer->importOrder();
      }

      public function actionManufacturer()
      {
      $this->importer->importManufacturer();
      }

      public function actionSales()
      {
      $this->importer->importSales();
      }

      public function actionTool()
      {

      }
     */

    /**
     * Новый семафор
     * @throws \yii\base\Exception
     */
    public function actionSemaphor() {
        $semaphor = new Semaphor();
        $semaphor->update();
    }

    // TODO тут пока старый вариант через новый метод получения данных
    // TODO и его надо бы переделать (ну или перенести)
    public function actionUpdateBalance() {
        $this->importer->updateBalance();
    }

    /**
     * Импорт фото из указанного каталога
     * @param null $path
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionPhoto($path = null) {
        $import = new Import();
        $import->importPhoto($path);
    }

    /**
     *  Новый импорт | вроде все есть
     *  запуск - yii import/import модель параметры
     *  ex.: yii import/import
     *
     * @param $type
     */
    public function actionImport($type, $args = null) {
        error_reporting(0);
        $import = new Import();

        $methodName = 'import' . ucfirst($type);

        if (method_exists($import, $methodName)) {
            $import->$methodName($args);
        } else {
            echo "FНет такой команды: {$type}" . PHP_EOL;
        }
    }

    public function actionTest() {
        $prod = Product::find()
                ->where(['id' => 2593])
                ->one();

        echo $prod->guid;

//		$prod->delete();
    }

    /**
     * Сюда пихать функции, которые должны работать по крону
     * @throws \ReflectionException
     */
    public function actionCron() {
        // синхронизация категорий
        $task = new CronTask();
        $task->refreshSales();
        $task->refreshNews();
    }

}
