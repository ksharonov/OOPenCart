<?php

/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 17:15
 */

namespace app\modules\lexema\api;

use app\helpers\ImportHelper;
use app\helpers\LexemaHelper;
use app\helpers\ModelRelationHelper;
use app\models\base\product\ProductPrice;
use app\models\db\LexemaOrder;
use app\models\db\Manufacturer;
use app\models\db\Order;
use app\models\db\Product;
use app\models\db\ProductAnalogue;
use app\models\db\ProductAssociated;
use app\models\db\ProductCategory;
use app\models\session\ReconciliationSession;
use app\modules\backoffice\models\Lexema;
use app\modules\lexema\api\base\IConnection;
use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\connection\FileConnection;
use app\modules\lexema\api\db\LexemaCard;
use app\modules\lexema\api\db\LexemaClient;
use app\modules\lexema\api\db\LexemaManufacturer;
use app\modules\lexema\api\db\LexemaApiOrder;
use app\modules\lexema\api\db\LexemaPriceGroup;
use app\modules\lexema\api\db\LexemaProduct;
use app\modules\lexema\api\db\LexemaProductCategory;
use app\modules\lexema\api\db\LexemaProduction;
use app\modules\lexema\api\db\LexemaProductPrice;
use app\modules\lexema\api\db\LexemaShop;
use app\modules\lexema\api\db\LexemaStorage;
use app\modules\lexema\api\db\LexemaUnit;
use app\modules\lexema\api\db\LexemaUser;
use app\modules\lexema\api\repository\AnalogueRepository;
use app\modules\lexema\api\repository\AssociatedRepository;
use app\modules\lexema\api\repository\CardRepository;
use app\modules\lexema\api\repository\ClientRepository;
use app\modules\lexema\api\repository\ManufacturerRepository;
use app\modules\lexema\api\repository\OrderRepository;
use app\modules\lexema\api\repository\PriceGroupRepository;
use app\modules\lexema\api\repository\PriceRepository;
use app\modules\lexema\api\repository\ProductCategoryRepository;
use app\modules\lexema\api\repository\ProductionRepository;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\repository\ReconciliationRepository;
use app\modules\lexema\api\repository\SalesRepository;
use app\modules\lexema\api\repository\ShopRepository;
use app\modules\lexema\api\repository\StorageRepository;
use app\modules\lexema\api\repository\UnitRepository;
use app\modules\lexema\api\repository\UserRepository;
use app\modules\lexema\models\OrderExport;

class Import {

    protected $source;

    public function __construct(IConnection $source = null) {
        if ($source == null) {
            $this->source = CookieAuthConnection::get();
            //$this->source = FileConnection::get();
        } else {
            $this->source = $source;
        }
    }

    /** ------------------------------- */
    /*     * ----Справочники(Независимые)----- */

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    public function importUnit() {
        $units = UnitRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($units as &$unit) {
            $dbUnit = LexemaUnit::findByGuid($unit['GlobalId']);

            if ($dbUnit) {
                //$dbUnit->loadFromRemote($unit);
                //$dbUnit->save();
            } else {
                $dbUnit = new LexemaUnit($unit);
                $dbUnit->save();
            }
        }
    }

    /**
     * Рабочий вариант
     * @throws \Exception
     */
    public function importCard() {
        $cards = CardRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($cards as &$card) {
            /** @var LexemaCard $dbCard */
            $dbCard = LexemaCard::find()
                    ->where(['number' => $card['CardNumber']])
                    ->one();

            if ($dbCard) {
                //upd
                $dbCard->loadFromRemote($card);
                $dbCard->save();
            } else {
                $dbCard = new LexemaCard($card);
                $dbCard->save();
            }
        }
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    public function importCategory() {
        $categories = ProductCategoryRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($categories as $category) {
            $dbCategory = LexemaProductCategory::findByGuid($category['globalId']);

            if ($dbCategory) {
                // upd
                //$dbCategory->loadFromRemote($category);
                //$dbCategory->save();
                continue;
            } else {
                $dbCategory = new LexemaProductCategory($category);
                $dbCategory->save();
            }
        }

        LexemaHelper::addCategorySettings();
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    public function importPriceGroup() {
        $priceGroups = PriceGroupRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($priceGroups as $priceGroup) {
            $dbPriceGroup = LexemaPriceGroup::findByGuid($priceGroup['GlobalId']);

            if ($dbPriceGroup) {
                //upd
                //$dbPriceGroup->loadFromRemote($priceGroup);
                //$dbPriceGroup->save();
            } else {
                $dbPriceGroup = new LexemaPriceGroup($priceGroup);
                $dbPriceGroup->save();
            }
        }

        LexemaHelper::addPriceGroupSettings();
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    public function importShop() {
        $shops = ShopRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($shops as $shop) {
            //$transaction = \Yii::$app->db->beginTransaction();
            $dbShop = LexemaShop::findByGuid($shop['globalId'], true);

            if ($dbShop) {
                //upd
                /*
                  $dbShop->loadFromRemote($shop);

                  if ($dbShop->save(false)) {
                  $transaction->commit();
                  }
                 */

                continue;
            } else {
                $dbShop = new LexemaShop($shop);
                $dbShop->save(false);

                /*
                  if ($dbShop->save(false)) {
                  $transaction->commit();
                  }
                 */
            }
        }

        LexemaHelper::refreshCitiesOnSite();
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     * @throws \yii\db\Exception
     */
    public function importStorage() {
        $storages = StorageRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($storages as $storage) {
            //$transaction = \Yii::$app->db->beginTransaction();
            $dbStorage = LexemaStorage::findByGuid($storage['globalId']);

            if ($dbStorage) {
                //upd
                /*
                  $dbStorage->loadFromRemote($storage);

                  if ($dbStorage->save(false)) {
                  $transaction->commit();
                  }
                 */
            } else {
                $dbStorage = new LexemaStorage($storage);
                $dbStorage->save(false);

                /*
                  if ($dbStorage->save(false)) {
                  $transaction->commit();
                  }
                 */
            }
        }
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    public function importUser() {
        $users = UserRepository::get()
                ->find()
                ->all();

        foreach ($users as $user) {
            $dbUser = LexemaUser::findByGuid($user['ContractorGlobalId']);
            if ($dbUser) {
                //upd
                $dbUser->loadFromRemote($user);
                $dbUser->save();
            } else {
                $dbUser = new LexemaUser($user);
                $dbUser->save();
            }
        }
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    //ESI_GetReestrContract&Contractor=$client->guid
    //ESI_GetDocumentFiles&Vcode=$vcode&Tdoc=$tdoc
    public function importClient() {
        $clients = ClientRepository::get()
                ->find()
                ->all();

        foreach ($clients as $client) {
            $dbClient = LexemaClient::findByGuid($client['ContractorGlobalId']);

            if ($dbClient) {
                //upd
                $dbClient->loadFromRemote($client);
                $dbClient->save(false);
            } else {
                $dbClient = new LexemaClient($client);
                $dbClient->save(false);
            }
        }
    }

    /** ------------------------------- */
    /*     * ------------Зависимые------------ */

    /**
     * Вроде работает
     * @throws \ReflectionException
     */
    public function importAssociated() {
        $assocs = AssociatedRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        if ($assocs) {
            foreach ($assocs as $apiAssoc) {
                $dbChild = LexemaProduct::findByGuid($apiAssoc['ProductglobalId']);
                $dbParent = LexemaProduct::findByGuid($apiAssoc['parentProductglobalId']);

                if ($dbChild && $dbParent) {
                    $dbAssoc = new ProductAssociated();
                    $dbAssoc->productId = $dbParent->id;
                    $dbAssoc->productAssociatedId = $dbChild->id;
                    $dbAssoc->save();
                    echo "Добавлено: Ассоциативный товар " . $dbChild->title . PHP_EOL;
                }
            }
        }
    }

    /**
     * Вроде работает
     * @throws \ReflectionException
     */
    public function importAnalogue() {
        $analogs = AnalogueRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        if ($analogs) {
            foreach ($analogs as $apiAnalog) {
                $dbChild = LexemaProduct::findByGuid($apiAnalog['ProductglobalId']);
                $dbParent = LexemaProduct::findByGuid($apiAnalog['parentProductglobalId']);

                if ($dbChild && $dbParent) {
                    $dbAnalogue = new ProductAnalogue();
                    $dbAnalogue->productId = $dbParent->id;
                    $dbAnalogue->productAnalogueId = $dbChild->id;
                    $dbAnalogue->save();
                    echo "Добавлено: Аналог " . $dbChild->title . PHP_EOL;
                }
            }
        }
    }

    // TODO
    public function importOrder() {
        $orders = OrderRepository::get()
                ->find()
                ->all();

        if (!$orders) {
            return false;
        }

        foreach ($orders as $apiOrder) {
            /** @var LexemaApiOrder $dbLexemaOrder */
            $dbLexemaOrder = LexemaApiOrder::findByOrderId($apiOrder['OrderId']);

            if ($dbLexemaOrder) {
                $dbLexemaOrder->loadFromRemote($apiOrder);
                $dbLexemaOrder->save(false);
            } else {
                $dbLexemaOrder = new LexemaApiOrder($apiOrder);
                $dbLexemaOrder->save();
            }
        }
    }

    // TODO только сертификаты
    public function importFile() {
        $products = LexemaProduct::find()
                ->leftJoin('file', 'file.relModel = 0 AND file.relModelId = product.id');
    }

    /** @deprecated Импортируется вместе с товарами!!! */
    public function importProductPrice() {
        $prices = PriceRepository::get()
                ->find()
                ->one();

        dump($prices);

        $transaction = \Yii::$app->db->beginTransaction();
        $dbPrice = new LexemaProductPrice($prices);
        $dbPrice->save();

        dump($dbPrice);

        $transaction->rollBack();
    }

    /**
     * Рабочий вариант
     * @throws \ReflectionException
     */
    public function importBrand() {
        $manufacturers = ManufacturerRepository::get()
                ->setSource($this->source)
                ->find()
                ->all();

        foreach ($manufacturers as $manufacturer) {
            $dbManufacturer = LexemaManufacturer::findByGuid($manufacturer['GlobalBrendId']);

            if ($dbManufacturer) {
                continue;
                $dbManufacturer->loadFromRemote($manufacturer);
                $dbManufacturer->save();
            } else {
                $dbManufacturer = new LexemaManufacturer($manufacturer);
                $dbManufacturer->save();
            }
        }
    }

    // TODO импорт товаров поставщиков
    public function importProduction() {
        $apiProduction = ProductionRepository::get()
                ->find()
                ->all();

        foreach ($apiProduction as $apiProduct) {
            $dbProduct = LexemaProduction::findByGuid($apiProduct['vcode']);

            if ($dbProduct) {
                $dbProduct->loadFromRemote($apiProduct);
                $dbProduct->save();
            } else {
                $dbProduct = new LexemaProduction($apiProduct);
                $dbProduct->save();
            }
        }
    }

    /**
     * Новый импорт (постоянно меняю это говно...)
     * Только добавляет новый товар
     * @throws \ReflectionException
     */
    public function importProduct() {
        $categories = ProductCategory::find()
                ->where(['isDefault' => 0])
                ->all();

        $totalCategories = count($categories);
        $counterCategory = $totalCategories;
        foreach ($categories as $category) {
            $apiProducts = ProductRepository::get()
                    ->setSource($this->source)
                    ->find(['parentglobalId' => $category->guid])
                    ->all();

            if ($apiProducts) {
                echo 'Импорт в категорию ' . $category->title . PHP_EOL;
                $total = count($apiProducts);
                $counter = $total;

                foreach ($apiProducts as $apiProduct) {
                    $dbProduct = LexemaProduct::findByGuid($apiProduct['globalId']);

                    if ($dbProduct) {
                        // НЕЛЬЗЯ ОБНОВЛЯТЬ

                        /** --------------- */
                        $dbProduct->setDbAttributes('height', $apiProduct['height']);
                        $dbProduct->setDbAttributes('length', $apiProduct['length']);
                        $dbProduct->setDbAttributes('width', $apiProduct['width']);
                        $dbProduct->setDbAttributes('weight', $apiProduct['weight']);
                        $dbProduct->setDbAttributes('volume', $apiProduct['volume']);

                        $dbProduct->save();
                        /** --------------- */
                    } else {
                        $dbProduct = new LexemaProduct($apiProduct);
                        $dbProduct->_category = $category;
                        $dbProduct->save();
                        //if (dump($dbProduct->getErrors()));exit;
                    }

                    echo 'Осталось ' . $counter-- . " / " . $total . " | " .
                    "Категория: " . $counterCategory . " / " . $totalCategories . "\r";
                }
            }
            $counterCategory--;
        }
    }

    /**
     *  Для импорта фоток из папки
     *  (по дефолту app/web/files/photos)
     *  ВНИМАНИЕ!
     * 	Удаляет все имеющиеся фотки
     *  и заменяет на новую
     *
     * @param null $path
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function importPhoto($path = null) {
        if (!$path) {
            $path = \Yii::getAlias('@webroot') . 'files/photos/';
        }

        if (!is_dir($path)) {
            throw new \Exception($path . ' не каталог');
        }

        $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        $codes = [];
        $start = time();
        foreach ($directory as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isFile()) {
                $code = $file->getBasename('.' . $file->getExtension());
                //$checkSum = md5_file($file->getPathname());
                $codes[$code] = $file->getPathname();
            }
        }

        unset($directory);

        echo "В директории " . $path . ' найдено ' . count($codes) . " файлов" . PHP_EOL;


        $products = Product::find()
                ->where(['backCode' => array_keys($codes)])
                ->with('images')
                ->all();

        echo 'Совпадений: ' . count($products) . PHP_EOL;

        $totalCount = count($products);
        $counter = $totalCount;
        foreach ($products as $product) {
            /** @var Product $product */
            $vcode = $product->backCode;

            if ($product->images) {
                // удаляем старые фоты
                foreach ($product->images as $dbImage) {
                    $realFilePath = \Yii::getAlias("@app/web{$dbImage->path}");
                    $dbImage->delete();
                    $result = @unlink($realFilePath);
                }
            }
            ImportHelper::addPhoto($codes[$vcode], $product);
            $counter--;
            echo "Осталось: " . $counter . " / " . $totalCount . "\r";
        }

        dump(time() - $start);
    }

    /**
     *  Полный импорт остатков
     */
    public function importBalances() {
        /** @var LexemaProduct[] $products */
        $products = LexemaProduct::find()
                ->all();

        $total = count($products);
        $counter = $total;
        foreach ($products as $product) {
            $product->setDbBalances();
            $counter--;
            echo "Осталось: " . $counter . " / " . $total . PHP_EOL . "\r";
        }
    }

    public function test() {
        $string = 'hello world';



        return;
        $guid = '7085325c-215e-11e5-a014-008048319a51';

        $sales = SalesRepository::get()
                ->find(['TovarglobalId' => $guid])
                ->one();

        dump($sales);

        $prod = ProductRepository::get()
                ->findByProductGuid($guid)
                ->one();

        dump($prod);

        $prices = PriceRepository::get()
                ->find(['ProductGlobalId' => $guid])
                ->all();

        dump($prices);
        return;
    }

    /**
     * Импортирует/обновляет цены у товаров, у которых вообще нет цен
     */
    public function importPrices() {
        $products = LexemaProduct::find()
                ->select('product.*, GROUP_CONCAT(DISTINCT pc.productPriceGroupId) as sqlPrices')
                ->leftJoin('product_price pc', 'pc.productId = product.id')
                ->groupBy('product.id')
                ->having('GROUP_CONCAT(DISTINCT pc.productPriceGroupId) is NULL')
                ->all();

        foreach ($products as $product) {
            /** @var LexemaProduct $product */
            $product->setDbPrice();
        }
    }

    /**
     * Импорт/обновление цен у ВСЕХ товаров
     */
    public function importAllPrices() {
        echo 'Загружаю все цены из Лексемы...\n';
        $products = LexemaProduct::find()
                ->all();

        $total = count($products);
        $counter = $total;
        echo 'Обновляю наши прайсы...\n';
        foreach ($products as $product) {
            /** @var LexemaProduct $product */
            $product->setDbPrice();
            $counter--;
            echo 'Осталось:: ' . $counter . " / " . $total . "\r";
        }
    }

    /** 	 	Одноразовые функции			 */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */
    /** ------------------------------------ */

    /**
     * можно запускать после импорта производителей
     * выключен!
     * @deprecated
     */
    public function restoreManufacturer() {
        return;
        \Yii::$app->db->close();
        \Yii::$app->db->dsn = 'mysql:host=127.0.0.1;port=3310;dbname=220v4';

        $newBrands = Manufacturer::find()
                ->all();

        foreach ($newBrands as &$newBrand) {
            $newBrand->guid = strtoupper($newBrand->getGuid());
        }

        \Yii::$app->db->close();
        \Yii::$app->db->dsn = 'mysql:host=127.0.0.1;port=3310;dbname=220v3';
        $oldBrands = Manufacturer::find()
                ->all();

        $oldBrandsArray = [];
        foreach ($oldBrands as $oldBrand) {
            $guid = strtoupper($oldBrand->getGuid());
            $oldBrandsArray[$guid]['title'] = $oldBrand->title;
            $oldBrandsArray[$guid]['slug'] = $oldBrand->slug;
            $oldBrandsArray[$guid]['description'] = $oldBrand->description;
        }

        unset($oldBrand);

        \Yii::$app->db->close();
        \Yii::$app->db->dsn = 'mysql:host=127.0.0.1;port=3310;dbname=220v4';

        foreach ($newBrands as &$brand) {
            $guid = strtoupper($brand->guid);
            if (isset($oldBrandsArray[$guid])) {
                /** @var Manufacturer $brand */
                $brand->title = $oldBrandsArray[$guid]['title'];
                $brand->description = $oldBrandsArray[$guid]['description'];
                $brand->slug = $oldBrandsArray[$guid]['slug'];
                $brand->save();
            }
        }
    }

    /**
     * Запуск после импорта товаров
     * выключен!
     * @deprecated
     */
    public function restoreProduct() {
        return;
        \Yii::$app->db->close();
        \Yii::$app->db->dsn = 'mysql:host=127.0.0.1;port=3310;dbname=220v3';
        //\Yii::$app->db->dsn = 'mysql:host=127.0.0.1;dbname=220v3';

        $oldProducts = Product::find()
                ->indexBy('backCode')
                ->with('files')
                ->all();

        \Yii::$app->db->close();
        \Yii::$app->db->dsn = 'mysql:host=127.0.0.1;port=3310;dbname=220v4';
        //\Yii::$app->db->dsn = 'mysql:host=127.0.0.1;dbname=220vTest';

        $products = Product::find()
                ->all();

        $total = count($products);
        $counter = $total;
        foreach ($products as &$product) {
            $vcode = $product->backCode;
            if (isset($oldProducts[$vcode])) {
                $product->content = $oldProducts[$vcode]->content;
                $product->save(false);

                if ($oldProducts[$vcode]->files) {
                    foreach ($oldProducts[$vcode]->files as $oldFile) {
                        /** @var \app\models\db\File $oldFile */
                        $oldRealPath = \Yii::getAlias("@app/web{$oldFile->path}");
                        $newDirPath = \Yii::getAlias("@app/web/files/import/product/{$product->backCode}");
                        $filename = substr($oldRealPath, strrpos($oldRealPath, '/') + 1);
                        $pathToCopy = $newDirPath . "/" . $filename;

                        if (file_exists($oldRealPath)) {


                            if (!is_dir($newDirPath)) {
                                mkdir($newDirPath, 0777, true);
                            }

                            if (is_file($pathToCopy)) {
                                continue;
                            }

                            $copyResult = copy($oldRealPath, $pathToCopy);

                            if ($copyResult) {
                                $dbFile = new \app\models\db\File();
                                $dbFile->type = $oldFile->type;
                                $dbFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                                $dbFile->relModelId = $product->id;
                                $dbFile->status = \app\models\db\File::FILE_PUBLISHED;
                                $dbFile->path = "/files/import/product/{$product->backCode}/{$filename}";
                                //$dbFile->title = $filename;
//								$dbFile->save();
                                if ($dbFile->save()) {
                                    echo "Скопировано: Файл " . $product->backCode . " " . $product->title . PHP_EOL;
                                }
                            }
                        }

                        //dump($oldRealPath);exit;
                    }
                }

                //echo "Обновление: " . $product->backCode . " " . $product->title . PHP_EOL;
            }
            $counter--;
            echo "Осталось: " . $counter . " / " . $total . "\r";
        }

        \Yii::$app->db->close();
    }

    /**
     * Чистка товаров...
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function importClear() {
        $products = Product::find()
                ->innerJoin('product_to_category ptc', 'ptc.productId = product.id
				AND (ptc.categoryId = 79 OR ptc.categoryId = 80)')
                ->all();

        foreach ($products as $product) {
            $std = 0;
            $notStd = 0;
            foreach ($product->categories as $category) {
                if ($category->id == 79 || $category->id == 80) {
                    $std++;
                } else {
                    $notStd++;
                }
            }
            if (!$notStd) {
                $apiProduct = ProductRepository::get()
                        ->findByProductGuid($product->guid)
                        ->one();

                $dbCategory = ProductCategory::findByGuid($apiProduct['parentglobalId'], true);

                if (!$dbCategory) {
                    if ($product->delete()) {
                        echo 'Cleared: ' . $product->title . PHP_EOL;
                    } else {
                        echo 'WTF';
                    }
                } else {
                    $product->link('categories', $dbCategory);
                }
            }
        }
    }

}
