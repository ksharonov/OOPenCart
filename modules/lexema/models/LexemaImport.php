<?php

namespace app\modules\lexema\models;

use app\helpers\LexemaHelper;
use app\helpers\ModelRelationHelper;
use app\helpers\StringHelper;
use app\models\db\Address;
use app\models\db\CityOnSite;
use app\models\db\City;
use app\models\db\Client;
use app\models\db\Contract;
use app\models\db\File;
use app\models\db\LexemaCard;
use app\models\db\Manufacturer;
use app\models\db\Order;
use app\models\db\OrderContent;
use app\models\db\LexemaOrder;
use app\models\db\OuterRel;
use app\models\db\Product;
use app\models\db\ProductAnalogue;
use app\models\db\ProductAssociated;
use app\models\db\ProductCategory;
use app\models\db\ProductPrice;
use app\models\db\ProductPriceGroup;
use app\models\db\ProductPriceGroupToClient;
use app\models\db\ProductToCategory;
use app\models\db\ProductUnit;
use app\models\db\Setting;
use app\models\db\Storage;
use app\models\db\StorageBalance;
use app\models\db\Unit;
use app\models\db\User;
use app\models\db\UserToClient;
use app\modules\lexema\api\base\BaseRepository;
use app\modules\lexema\api\base\QueryRepository;
use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\connection\HttpConnection;
use app\modules\lexema\api\db\LexemaProduction;
use app\modules\lexema\api\repository\CardRepository;
use app\modules\lexema\api\repository\ProductionRepository;
use app\modules\lexema\api\repository\ReconciliationRepository;
use app\modules\lexema\api\repository\TestProduction;
use app\modules\lexema\models\db\ApiCategoryBehavior;
use app\modules\lexema\models\db\LexemaProductCategory;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * Description of LexemaImport
 *
 * @author pavel
 */
class LexemaImport {

    private $connection = null;
    // хранилище данных взятых из апи между вызовами методов
    private $dataArray = null;
    // ассоциация параметра запроса => имя метода
    private $model = [
        'ESI_GetProduct' => 'importProduct',
        'ESI_GetContractor' => 'importClient',
        'ESI_Edizm' => 'importUnit',
        'ESI_Analogue' => 'importAnalogue',
        'ESI_TovarSoput' => 'importAssociated',
        'ESI_TypePriceZena' => 'importPriceGroup',
        'ESI_Price' => 'importPrice',
        'ESI_GetStorage' => 'importStorage',
        'ESI_GetShop' => 'importStorage',
        'ESI_GetChangeStorage' => 'importBalanceByGuid',
        'ESI_GetProductImages' => 'importFileByGuid',
        'ESI_GetLoggin' => 'importUser',
        'ESI_GetFolderProduct' => 'importCategory',
        'ESI_ReconciliationAct' => 'getReconciliationAct',
        'ESI_GetSalesProduct' => 'importSales',
        'ESI_GetReestrEZK' => 'updateOrder',
    ];
    // массив соответствий типов файлов
    private $fileTypes = [
        '47061' => File::TYPE_CERTIFICATE,
        '47062' => File::TYPE_IMAGE,
    ];
    private $logsPath = "";

    /** @var array $prices		Массив со всеми прайсами  */
    public $prices = null;

    /** @var array $balances 	Массив со всеми остаткиами */
    public $balances = null;

    public function __construct() {
//    	if ($connection) {
//			if ($connection->getToken() === false){
//				throw new \Exception('Ошибка получения токена');
//			}
//
//			$this->connection = $connection;
//		}
        $this->connection = CookieAuthConnection::get();

        $this->logsPath = \Yii::getAlias('@app/runtime/logs/');

        if (!is_dir($this->logsPath))
            mkdir($this->logsPath);
    }

    /**
     *  Импорт едениц измерений (unit)
     */
    public function importUnit() {

        $relModel = ModelRelationHelper::REL_MODEL_UNIT;

        $units = $this->connection->queryArray('ESI_Edizm');

        foreach ($units as $unit) {
            $unitRel = OuterRel::find()
                    ->where(['relModel' => $relModel])
                    ->andWhere(['guid' => $unit['GlobalId']])
                    ->limit(1)
                    ->one();

            if ($unitRel) {
                // есть в таблице outer_rel
                if ($unitRel->unit) {
                    // и в таблице unit - обновляем
                    $unitRel->unit->title = $unit['name'];
                    $unitRel->unit->save();

                    echo 'Unit "' . $unit['name'] . '" updated' . "\n\r";
                } else {
                    // есть в outer_rel, но нет в unit
                    $newUnit = new Unit();
                    $newUnit->title = $unit['name'];
                    $newUnit->save();

                    $unitRel->relModelId = $newUnit->id;
                    $unitRel->save();

                    echo $unit['name'] . ' found in "outer_rel", but not in units. Adding to "unit".' . "\n\r";
                    flush();
                }
            } else {
                // нет в outer_rel
                $tUnit = Unit::find()
                        ->where(['title' => $unit['name']])
                        ->limit(1)
                        ->one();

                if ($tUnit) {
                    // есть в unit - добавляем связь
                    // !!! не знаю когда, но может случиться)
                    $tUnitRel = new OuterRel();
                    $tUnitRel->guid = $unit['GlobalId'];
                    $tUnitRel->relModel = $relModel;
                    $tUnitRel->relModelId = $tUnit->id;
                    $tUnitRel->save();

                    echo $unit['name'] . ' has found in table "unit", but there is no relation. Adding to "outer_rel".'
                    . "\n\r";
                    flush();
                } else {
                    // нет ни там, ни там - добавляем новый юнит
                    $newUnit = new Unit();
                    $newUnit->title = $unit['name'];
                    $newUnit->save();

                    $newUnitRel = new OuterRel();
                    $newUnitRel->guid = $unit['GlobalId'];
                    $newUnitRel->relModel = $relModel;
                    $newUnitRel->relModelId = $newUnit->id;
                    $newUnitRel->save();

                    echo $unit['name'] . ' added.' . "\n\r";
                }
            }
        }
        echo 'Unit import done.' . "\n\r";
        // старый вариант импорта едениц измерений
        /*
          $_currentUnits = OuterRel::find()->where(['relModel'=>$relModel])->all();

          // массив с текущими юнитами из БД (из таблицы связей) [guid => unit.title]
          if ($_currentUnits) {
          foreach ($_currentUnits as $unit) {
          //DebugHelper::dump($unit->unit->title);
          $currentUnits[$unit->guid] = $unit->unit->title;
          }
          } else $currentUnits[] = '';

          // массив с юнитами от Лексемы [GlobalId => name]
          if ($units) {
          foreach ($units as $unit) {
          $lexemaUnits[$unit->GlobalId] = $unit->name;
          }
          } else $units[] = '';

          $newUnits = array_diff_key($lexemaUnits, $currentUnits); // массив юнитов, которые надо ДОБАВИТЬ в бд
          $sameUnitsGuid = array_intersect_key($lexemaUnits, $currentUnits); // массив схождений полученных guid и guid из бд
          $changedUnits = array_diff($sameUnitsGuid, $currentUnits); //массив юнитов, которые надо ИЗМЕНИТЬ в бд

          // меняем старые значения юнитов (из БД) на новые (если имеются в Лексеме)
          if ($changedUnits) {
          foreach ($changedUnits as $guid => $name) {
          $outRel = OuterRel::find()->where(['guid' => $guid, 'relModel' => $relModel])->one();
          $id = $outRel->relModelId;

          $unit = Unit::find()->where(['id' => $id])->one();
          $unit->title = $name;
          $unit->save();
          }
          }

          // добавляем новые юниты в БД (если имеются в Лексеме)
          if ($newUnits) {
          foreach ($newUnits as $guid => $name) {
          $unit = new Unit();
          $unit->title = $name;
          $unit->save();

          $outRel = new OuterRel();
          $outRel->guid = $guid;
          $outRel->relModel = $relModel;
          $outRel->relModelId = $unit->id;
          $outRel->save();
          }
          }
         */
    }

    /** Обновление названий категорий продуктов и удаление из него чисел и символов,
     *  а так же обновление parentId
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @deprecated
     */
    private function updateCategories() {
        $cats = ProductCategory::find()->all();
        foreach ($cats as $category) {
            $st = $category->title;
            $st = trim(mb_ereg_replace("[^а-яА-Я\s]", "", $st));
            $category->title = $st;
            $category->save();
        }

        $sprav = ProductCategory::findByGuid('DE89E4BC-3637-4C39-B6FF-410C48371DDA', true);

        $spravRel = OuterRel::find()
                ->where(['guid' => 'DE89E4BC-3637-4C39-B6FF-410C48371DDA'])
                ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY])
                ->limit(1)
                ->one();

        $spravId = $sprav->id;
        //dump($spravId); exit;

        if (isset($sprav)) {
            $sprav->parentId = $spravId;
            $sprav->save();
        }
//        if (isset($spravRel)) $spravRel->delete();
//        $parents = ProductCategory::find()
//            ->where(['parentId' => $spravId])
//            ->all();
//
//        foreach ($parents as $category) {
//            $category->parentId = null;
//            $category->save();
//        }
        // оставляем первые 16 корневых категорий
        $parents = ProductCategory::find()
                ->where(['parentID' => $spravId])
                ->all();

        $parents = array_slice($parents, 0, 16);

        foreach ($parents as $category) {
            $category->parentId = null;
            $category->save();
        }
    }

    /** Импорт категорий продуктов (product_category)
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @deprecated
     */
    public function importProductCategory() {
        $relModel = ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY;

        $categories = $this->connection->query('ESI_GetFolderProduct', '\app\modules\lexema\jobjects\JProductCategory');

        $newCats = null; // инизиализация массива новых категорий для проставления parentId
        // проход по массиву с категориями из Лексемы
        foreach ($categories as $category) {
            // есть ли в таблице связей такой гуид
            $prodRel = OuterRel::find()
                    ->where(['guid' => $category->GlobalId])
                    ->andWhere(['relModel' => $relModel])
                    ->limit(1)
                    ->one();



            if ($prodRel) {
                // есть запись в таблице связей
                $tProd = ProductCategory::findOne($prodRel->relModelId);
                if ($tProd) {
                    // если есть и гуид в таблице связей и сама категория в таблице категорий
                    // апдейтим ее
                    $tProd->title = $category->name;
                    //if ($tProdParent) $tProd->parentId = $tProdParent->id;
                    $tProd->save();

                    echo $category->name . ' updated' . "\n\r";
                    flush();
                } else {
                    // если есть такой гуид в связях, но нет в такой категории
                    // добавляем ее
                    $newCat = new ProductCategory();
                    $newCat->title = $category->name;
                    $newCat->save();

                    // обновляем relModelId в таблице связей
                    $prodRel->relModelId = $newCat->id;
                    $prodRel->save();

                    echo $category->name . ' found in outer_rel, but not in categories. Adding.' . "\n\r";
                    flush();

                    // добавляем в $newCats[]
                    $newCats[][0] = $newCat;
                    $newCats[count($newCats) - 1][1] = $category->parentglobalId;
                }
            } else {
//                dump($category->GlobalId); exit;
                // нет записи в таблице связей
                // добавляем новую категорию
                $newCat = new ProductCategory();
                $newCat->title = $category->name;
                //$newCat->guid = $category->GlobalId;
                $newCat->save();
                //dump($newCat); exit;
                // и добавляем связь
                $prodRel = new OuterRel();
                $prodRel->guid = $category->GlobalId;
                $prodRel->relModel = $relModel;
                $prodRel->relModelId = $newCat->id;
                $prodRel->save();

                echo 'New category ' . $category->name . ' added.' . "\n\r";
                flush();

                // добавляем в $newCats[]
                $newCats[][0] = $newCat;
                $newCats[count($newCats) - 1][1] = $category->parentglobalId;
            }
        }

        echo 'Categories import done. Adding parent ids...' . "\n\r";

        // проставляем parentId
        foreach ($categories as $category) {
            $parentCatRel = OuterRel::find()->where(['guid' => $category->parentglobalId, 'relModel' => $relModel])->one();
            $catRel = OuterRel::find()->where(['guid' => $category->GlobalId, 'relModel' => $relModel])->one();
            //$catName = trim($category->name);
            $prodCat = ProductCategory::findOne($catRel->relModelId);

            if ($parentCatRel && $catRel && $prodCat) {
                $prodCat->parentId = $parentCatRel->relModelId;
                $prodCat->update();
            }
        }

        echo 'Parent ids added. Update category names...' . "\n\r";

        // форматируем названия категорий
        $this->updateCategories();

        echo 'Update done.' . "\n\r";
    }

    /** Работа с базой категорий
     * @param $source
     * @throws \ReflectionException
     */
    private function addCategory($source) {
        $dbCategory = ProductCategory::findByGuid($source['globalId']);

        if ($dbCategory->isNewRecord == true) {
            $dbCategory->title = trim(mb_ereg_replace("[^а-яА-Я\s]", "", $source['name']));
            $dbCategory->save();

            $dbParentCategory = ProductCategory::findByGuid($source['parentglobalId'], true);
            $dbCategory->parentId = $dbParentCategory->id ?? $dbCategory->id;
            $dbCategory->save();
            echo 'Категория "' . $dbCategory->title . '" добавлена.' . PHP_EOL;
        } else {
            echo 'Категория "' . $dbCategory->title . '" уже имеется.' . PHP_EOL;
        }
    }

    /** Пост обработка категорий
     * @param $data
     * @throws \ReflectionException
     */
    private function prepareCategories($data) {
        $dbDefaultCategory = ProductCategory::findByGuid('DE89E4BC-3637-4C39-B6FF-410C48371DDA');
        if ($dbDefaultCategory->isNewRecord == true) {
            $dbDefaultCategory->title = 'Справочник товаров, продукции, материалов';
            $dbDefaultCategory->save();
        }

        $dbDefaultCategory->parentId = -1;
        $dbDefaultCategory->save();

        foreach ($data as $apiCategory) {
            if ($apiCategory['globalId'] == $dbDefaultCategory->guid) {
                continue;
            }

            $dbCategory = ProductCategory::findByGuid($apiCategory['globalId'], true);
            $dbParentCategory = ProductCategory::findByGuid($apiCategory['parentglobalId'], true);

            if ($dbCategory && $dbParentCategory) {
                $dbCategory->parentId = $dbParentCategory->id;
                $dbCategory->save();
            }
        }

        $dbCategories = ProductCategory::find()
                ->where(['parentId' => $dbDefaultCategory->id])
                ->limit(17)
                ->all();

        foreach ($dbCategories as $dbCategory) {
            $dbCategory->parentId = null;
            $dbCategory->save();
        }
    }

    /** Импорт категорий
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importCategory($guid = null) {
        $result = $this->connection->queryArray('ESI_GetFolderProduct');

        if (isset($result['Message']) || !$result) {
            throw new \Exception('Возникла ошибка в API');
        }

        if ($guid) {
            foreach ($result as $category) {
                if ($category['globalId'] == $guid) {
                    $this->addCategory($category);
                }
            }

            return true;
        }

        foreach ($result as $apiCategory) {
            $this->addCategory($apiCategory);
        }

        $this->prepareCategories($result);

        echo 'Импорт категорий товаров завершен.' . PHP_EOL;
    }

    /**
     * @param $source
     * @return bool
     * @throws \ReflectionException
     */
    private function addProduct($source) {
        $tProduct = Product::findByGuid($source['globalId'], true);
        $category = ProductCategory::findByGuid($source['parentglobalId'], true);
        $unit = Unit::findByGuid($source['EdizmglobalId'], true);

        if (!$tProduct && $category && $unit) {
            $newProd = new Product();
            $newProd->title = $source['name'];
            $newProd->content = $source['note'];
            $newProd->fromRemote = true;
            $newProd->status = Product::STATUS_PUBLISHED;
            $newProd->save();

            // добавляем slugs
            $newProd->slug = $newProd->id . '_' . StringHelper::translit($newProd->title);
            $newProd->save(false);

            // связываем с product_category
            $newProd->link('categories', $category);

            // связываем с product_unit
            $newProdUnit = new ProductUnit();
            $newProdUnit->productId = $newProd->id;
            $newProdUnit->unitId = $unit->id;
            $newProdUnit->rate = 1;
            $newProdUnit->save();

            // запись в outer_rel
            $prodRel = new OuterRel();
            $prodRel->guid = $source['globalId'];
            $prodRel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
            $prodRel->relModelId = $newProd->id;
            $prodRel->save();

            echo "Товар \"" . $source['name'] . "\" добавлен. \n\r";
        } else {
            echo "Товар \"" . $source['name'] . "\" уже есть в базе. \n\r";
        }

        return true;
    }

    /** (тут использовано решение для оптимизации запросов к бд (array_column)  */

    /** Импорт продуктов (product)
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importProducts($guid = null) {
        /*
         * {
          globalId: "67E0965F-385F-4ECC-96DF-0A42AFFD68CF",
          parentglobalId: "DE89E4BC-3637-4C39-B6FF-410C48371DDA",
          name: "Пакет "UNIEL"",
          note: null,
          pruduceglogalid: null,
          EdizmglobalId: "0036480D-666B-4043-BBB8-8228048D7DAE",
         * },
         */
        if ($guid) {
            $product = $this->connection->queryArray("ESI_GetProduct&FolderglobalId=$guid");
            $result = $this->addProduct($product[0]);
            return $result;
        }

        // guid'ы категорий, имеющихся в базе
        $prodCatsGuids = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY])
                //->limit()
                ->asArray()
                ->all();

        // guid'ы продуктов, имеющихся в базе
        $productGuids = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT])
                ->asArray()
                ->all();

        // 62 секунды
        /*
          foreach ($prodCatsGuids as $prodCat) {
          $products[$prodCat->productCategory->title] = $this->lConnect->queryProducts($prodCat->guid);
          echo "Категория" .  $prodCat->productCategory->title . " заполнена <br>";
          flush();
          }
         */

        // 55 секунд
        /*
          for ($i=0; $i<count($prodCatsGuids); $i++) {
          $products[$prodCatsGuids[$i]->productCategory->title] = $this->lConnect->queryProducts($prodCatsGuids[$i]->guid);
          echo "Категория" .  $prodCatsGuids[$i]->productCategory->title . " заполнена <br>";
          flush();
          }
         */

        // progress
        $prods = $this->connection->queryArray('ESI_GetProduct');
        $totalCount = count($prods);

        unset($prods);

        $onePercent = intval(ceil($totalCount / 100));
        $currentPercent = 0;
        $counter = 0;

        // выбор по guid'у категорий продуктов из Лексемы (parentglobalId)
        foreach ($prodCatsGuids as $prodCat) {

            $products = $this->connection->queryArray("ESI_GetProduct&ProductglobalId={$prodCat['guid']}");

            // это уже поиск ПРОДУКТА по его гуиду
            if (isset($products)) {
                foreach ($products as $product) {
                    $isNew = $product['IsNewProduct'];
                    if (!isset($product['EdizmglobalId'])) {
                        continue;
                    }
                    $unit = OuterRel::find()
                            ->where(['guid' => $product['EdizmglobalId']])
                            ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_UNIT])
                            ->limit(1)
                            ->one();

                    if (isset($product['pruduceglogalid'])) {
                        $manufacturer = Manufacturer::findByGuid($product['pruduceglogalid'], true);
                    } else
                        $manufacturer = null;

                    // проверяем: есть ли в базе продукт с таким guid
                    $check = array_search($product['globalId'], array_column($productGuids, 'guid'));

                    if ($check === false) {
                        // если нет - добавляем
                        $category = ProductCategory::findOne($prodCat['relModelId']);

                        $newProd = new Product();
                        $newProd->fromRemote = true;
                        $newProd->title = $product['name'];
                        $newProd->content = $product['note'];
                        $newProd->manufacturerId = isset($manufacturer) ? $manufacturer->id : null;
                        $newProd->vendorCode = $product['artikul'];
                        $newProd->status = Product::STATUS_PUBLISHED;
                        $newProd->backCode = $product['vcode'];

                        $newProd->save(false);

                        // добавляем slugs
                        $newProd->slug = $newProd->id . '_' . StringHelper::translit($newProd->title);
                        $newProd->save(false);

                        // связываем с product_category
                        $newProd->link('categories', $category);

                        // новинка
                        if ($isNew == 1) {
                            $newCategory = ProductCategory::find()
                                    ->where(['id' => Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID')])
                                    ->limit(1)
                                    ->one();

                            $newProd->link('categories', $newCategory);
                        }

                        // связываем с product_unit
                        $newProdUnit = new ProductUnit();
                        $newProdUnit->productId = $newProd->id;
                        $newProdUnit->unitId = $unit->unit->id;
                        $newProdUnit->rate = 1;
                        $newProdUnit->save();

                        // запись в outer_rel
                        $prodRel = new OuterRel();
                        $prodRel->guid = $product['globalId'];
                        $prodRel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                        $prodRel->relModelId = $newProd->id;
                        $prodRel->save();

                        echo "Товар \"" . $product['name'] . "\" добавлен. \n\r";
                    } else if ($check >= 0) {
                        // тут можно проверить, изменились ли данные
                        // и заапдейтить
                        //$counter++;
                        //continue;
//                        $tProd = OuterRel::find()
//                            ->where(['guid' => $product['globalId']])
//                            ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT])
//                            ->one();
                        $tProduct = Product::findByGuid($product['globalId'], true);

                        $tProdUnit = ProductUnit::find()
                                ->where(['productId' => $tProduct->id])
                                ->andWhere(['unitId' => $unit->unit->id])
                                ->limit(1)
                                ->one();

                        if (!$tProdUnit) {
                            $newProdUnit = new ProductUnit();
                            $newProdUnit->productId = $tProduct->id;
                            $newProdUnit->unitId = $unit->unit->id;
                            $newProdUnit->rate = 1;
                            $newProdUnit->save();
                            echo "Added unit " . $unit->unit->title . " to " . $product['name'] . "\n\r";
                        }

                        if ($tProduct->manufacturerId == null && isset($manufacturer->id)) {
                            $tProduct->manufacturerId = $manufacturer->id;
                            echo "К товару " . $tProduct->title . " добавлен производитель \"" . $manufacturer->id . "\"\n\r";
                        }

                        if ($tProduct->vendorCode == null) {
                            $tProduct->vendorCode = !($product['artikul']) ? $product['artikul1'] : $product['artikul'];
                            echo "К товару " . $tProduct->title . " добавлен артикул \"" . $product['artikul'] . "\"\n\r";
                        }

                        if ($tProduct->backCode != $product['vcode']) {
                            $tProduct->backCode = $product['vcode'];
                        }

                        // новинка
                        if ($isNew == 1 && $tProduct) {
                            $newCategory = ProductCategory::find()
                                    ->where(['id' => Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID')])
                                    ->limit(1)
                                    ->one();

                            $checkLink = ProductToCategory::find()
                                    ->where(['productId' => $tProduct->id])
                                    ->andWhere(['categoryId' => $newCategory->id])
                                    ->limit(1)
                                    ->one();

                            if (!$checkLink) {
                                $tProduct->link('categories', $newCategory);
                            }
                        }

                        $tProduct->save(false);
//                        echo "Товар \"" . $product['name'] . "\" уже есть в базе. \n\r";
                    }
                    $counter++;
                    $currentPercent = $counter / $onePercent;
                    echo "\r\r" . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
                    flush();
                }
            } else
                continue;

            unset($product);
            //unset($productsArray);
            //echo "Категория " .  $prodCat['guid'] . " заполнена \n\r";
            //$counter++;
            $currentPercent = $counter / $onePercent;
            echo "\r\r" . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
            flush();
        }
        echo "Импорт продуктов завершен \n\r";
    }

    public function saveProduct(Product $dbProduct, $source, $new = false) {
        $guid = $source['globalId'];
        $dbCategory = isset($source['parentglobalId']) ? ProductCategory::findByGuid($source['parentglobalId'], true) : null;
        $dbUnit = isset($source['EdizmglobalId']) ? Unit::findByGuid($source['EdizmglobalId'], true) : null;
        $dbManufacturer = isset($source['pruduceglogalid']) ? Manufacturer::findByGuid($source['pruduceglogalid']) : null;

        if ($dbCategory && $dbUnit) {
            echo 'Импорт товара "' . $source['name'] . '"... ';
            $dbProduct->fromRemote = true;
            $dbProduct->title = $source['name'];
            $dbProduct->content = str_replace(PHP_EOL, "<br>", $source['note']);
            $dbProduct->status = Product::STATUS_PUBLISHED;
            $dbProduct->manufacturerId = $dbManufacturer->id ?? null;
            $dbProduct->save();

            // slug
            $dbProduct->slug = $dbProduct->id . '_' . StringHelper::translit($dbProduct->title);
            $dbProduct->save();

            // category
            $dbProduct->link('categories', $dbCategory);

            // product unit
            $dbProduct->link('unit', $dbUnit);

            // extras
            echo 'Файлы... ';
            $this->importFileByGuid($guid);

            echo 'Аналоги... ';
            $this->importAnalogue($guid);

            echo 'Сопутствующие... ';
            $this->importAssociated($guid);

            if ($new === true) {
                // добавить цены и остатки
                echo 'Цены... ';
                $this->importPrice($guid);
                echo 'Остатки... ';
                $this->importBalanceByGuid($guid);
            }

            echo 'Выполнено.' . PHP_EOL;
        } else {
            echo 'Импорт товара "' . $source['name'] . '" невозможен: в БД отсутсвует необходимая единица измерения или категория.' . PHP_EOL;
        }
    }

    public function importProduct($guid = null) {
        $dbProduct = Product::findByGuid($guid);
        $apiProduct = $this->connection->queryArray("ESI_GetProduct&FolderglobalId=$guid");

        if (!isset($apiProduct[0])) {
            return false;
        }

        $this->saveProduct($dbProduct, $apiProduct[0], $dbProduct->isNewRecord);
    }

    /**
     *  Генерация slug'ов в таблицу продуктов
     */
    public function updateSlug() {
        $products = Product::find()
                ->where('slug IS NULL')
                ->all();

        foreach ($products as $product) {
            $product->slug = $product->id . '_' . StringHelper::translit($product->title);
            $product->save(false);
        }
    }

    /**
     *  Импорт групп прайсов (product_price_group)
     */
    public function importPriceGroup() {
        /*
         * {
         * "ProductGlobalId":"95DE1ED8-FCA1-4DC7-86D4-EDD846EC1316",
         * "EdizmGlobalId":"0036480D-666B-4043-BBB8-8228048D7DAE",
         * "TypeZenaGlobalId":"0036480D-666B-4043-BBB8-8228048D7DAE",
         * "ozena2":1241.0000
         * }
         */

        $priceGroupsRelModel = ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP;

        $lexPriceGroups = $this->connection->queryArray('ESI_TypePriceZena');

        foreach ($lexPriceGroups as $priceGroup) {
            $pgRel = OuterRel::find()
                    ->where(['relModel' => $priceGroupsRelModel])
                    ->andWhere(['guid' => $priceGroup['GlobalId']])
                    ->limit(1)
                    ->one();

            if ($pgRel) {
                if ($pgRel->productPriceGroup) {
                    // есть и там и там - апдейтим
                    $pgRel->productPriceGroup->title = $priceGroup['name'];
                    $pgRel->productPriceGroup->save();

                    echo $priceGroup['name'] . ' updated.' . "\n\r";
                    flush();
                } else {
                    // есть в outer_rel, но нет в price_group - добавляем
                    $newPGroup = new ProductPriceGroup();
                    $newPGroup->title = $priceGroup['name'];
                    $newPGroup->save();

                    $pgRel->relModelId = $newPGroup->id;
                    $pgRel->save();

                    echo $priceGroup['name'] . ' added to product_price_group.' . "\n\r";
                    flush();
                }
            } else {
                // нет такой прайс группы - добавляем
                $newPGroup = new ProductPriceGroup();
                $newPGroup->title = $priceGroup['name'];
                $newPGroup->save();

                $outRel = new OuterRel();
                $outRel->guid = $priceGroup['GlobalId'];
                $outRel->relModel = $priceGroupsRelModel;
                $outRel->relModelId = $newPGroup->id;
                $outRel->save();

                echo $priceGroup['name'] . ' added to outer_rel & product_price_group.' . "\n\r";
                flush();
            }
        }
        echo 'Импорт групп цен завершен.' . "\n\r";
    }

    /**
     * @param $guid
     * @param $prices
     * @return bool|void
     * @throws \ReflectionException
     */
    private function addPrice($guid, $prices) {
        $product = Product::findByGuid($guid, true);

        // Массив с имеющимися группами прайсов
        $tPriceGroup = OuterRel::find()
                ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP])
                ->asArray()
                ->all();

        // Перевод его в вид [id группы => guid группы]
        foreach ($tPriceGroup as $price) {
            $priceGroup[$price['relModelId']] = $price['guid'];
        }

        if (!$product) {
            return;
        }

        foreach ($prices as $price) {
            // Поиск id группы из массива $priceGroup
            $priceGroupId = array_search($price['TypeZenaGlobalId'], $priceGroup);

            // Есть ли уже в БД продукт с соотношением id_прайса и id_группы_прайсов
            $checkProduct = ProductPrice::find()
                    ->where(['productId' => $product->id])
                    ->andWhere(['productPriceGroupId' => $priceGroupId])
                    ->limit(1)
                    ->one();

            if ($checkProduct) {
                // Если есть - можно апдейтить цены
                $checkProduct->value = ($checkProduct->value == $price['ozena2']) ? $price['ozena2'] : $checkProduct->value;
                $checkProduct->save();
                $status = $checkProduct->product->title . " updated ***";
            } else {
                // Если нет - добавляем новую (если есть)
                if ($priceGroupId) {
                    // Если есть guid прайс группы, то проставляем ее
                    $tPrice = new ProductPrice();
                    $tPrice->productId = $product->id;
                    $tPrice->productPriceGroupId = $priceGroupId;
                    $tPrice->value = $price['ozena2'];
                    $tPrice->save();
                    $status = "added +++";
                } else {
                    // Если нет - ставим null
                    // Если нет такой группы - пока не добавляем в бд
                    /*
                      $tPrice = new ProductPrice();
                      $tPrice->productId = $product->relModelId;
                      $tPrice->productPriceGroupId = null;
                      $tPrice->value = $value['ozena2'];
                      $tPrice->save();
                     */
                    $status = "cannot find price group ---";
                }
            }
        }

        echo $status . "\n\r";

        return true;
    }

    /** Импорт цен на продукты, работает долго(100к+ элементов) (product_price)
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importPrice($guid = null) {
        /*
         * {
         * ["ProductGlobalId"]=>"F4CC7CBF-2123-48E8-97E7-7D6AB07582CC"
         * ["EdizmGlobalId"]=>"0036480D-666B-4043-BBB8-8228048D7DAE"
         * ["TypeZenaGlobalId"]=>"0036480D-666B-4043-BBB8-8228048D7DAE"
         * ["ozena2"]=>float(1.7)
         *  }
         */

        if (!$this->prices) {
            // Массив со ВСЕМИ прайсами из Лексемы (100к+ элементов)
            $prices = $this->connection->queryArray('ESI_Price');

            // Преобразование массива с прайсами из Лексемы в удобный вид
            // где key - guid продукта, value - массив со всеми его ценами
            // $_prices
            foreach ($prices as $price) {
                //if ($i > 50) break;
                $_prices[$price['ProductGlobalId']][]['EdizmGlobalId'] = $price['EdizmGlobalId'];
                $k = count($_prices[$price['ProductGlobalId']]) - 1;
                $_prices[$price['ProductGlobalId']][$k]['TypeZenaGlobalId'] = $price['TypeZenaGlobalId'];
                $_prices[$price['ProductGlobalId']][$k]['ozena2'] = $price['ozena2'];
                //$i++;
            }

            $this->prices = $_prices;

            unset($prices);
        } else {
            $_prices = $this->prices;
        }


        if ($guid) {
            //$prices = $_prices[$guid];
            if (isset($_prices[$guid])) {
                $prices = $_prices[$guid];
            } else {
                return true;
            }
            $this->addPrice($guid, $prices);
            return true;
        }

        // для вывода прогресса
        $full = count($_prices);
        $onePercent = intval(ceil($full / 100));
        $currentPercent = 0;
        $counter = 0;

        // Цикл, обходящий каждый элемент $_prices (17к+ элементов)
        foreach ($_prices as $guid => $price) {
            $this->addPrice($guid, $price);


//            echo $checkProduct->id . $status . "\n\r";
//            flush();
            // ***** прогресс
            $counter++;
            $currentPercent = $counter / $onePercent;
            echo "\r\r" . $guid . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
            flush();
        }
        echo "Импорт прайсов завершен. \n\r";
    }

    /** Импорт файлов, долго (проверка, создание файлов, примерно 16к*3 элементов) (file)
     * @param string $mode - режим работы метода
     *          all             - перебирает все продукты
     *          empty           - перебирает только те, у которых вообще нет файлов
     *          onlyimages      - все продукты, только фотографии
     *          withoutimages   - все продукты, все, кроме фотографий
     * @param int $from - с какого id начать
     */
    public function importFile($mode = "all", $from = 0) {
        // массив с гуидами всех продуктов в базе
        $tProducts = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT])
                ->andWhere("relModelId >= $from")
//            ->andWhere('relModelId < 15000')
                ->orderBy('relModelId ASC')
                ->all();

        if ($mode == "empty") {
            foreach ($tProducts as $product) {
                if ($product->product->files == [] &&
                        $product->product->images == [])
                    $products[] = $product;
            }
            if (!isset($products))
                die('Нет продуктов без файлов');
        } else {
            $products = $tProducts;
        }

        unset($tProducts);

        // ******для вывода прогресса ))
        $full = count($products);
        $onePercent = intval(ceil($full / 100));
        $currentPercent = 0;
        $counter = 0;

        // создание папки под импорт файлов
        if (!is_dir(\Yii::getAlias('@webroot') . "files/import/products/")) {
            mkdir(\Yii::getAlias('@webroot') . "files/import/products/", 0777, true);
        }

        $errorProducts = [];

        // обход по всем продуктам
        foreach ($products as $product) {
            $guid = $product->guid;
            $id = $product->relModelId;
            $path = "files/import/products/$id/";
            $absPath = \Yii::getAlias('@webroot') . "files/import/products/$id/";
            $files = $this->connection->queryArray("ESI_GetProductImages&GlobalIdMaterial=$guid");

            // обход по найденным файлам текущего гуида
            foreach ($files as $file) {
                // иногда вылезает ошибка, поэтому пока оставил для дебага
                if (!isset($file['ftype'])) {
                    continue;
                }

                $type = $file['ftype']; // тип файла (47062, 47061)
                // если режим "только фотографии", но файл - не фотография
                if ($mode == "onlyimages" && $type != 47062)
                    continue;
                // если режим "все, кроме фотографий", но файл - фотография
                if ($mode == "withoutimages" && $type == 47062)
                    continue;

                $data = $file['file1']; // base64 данные
                $filename = $path . $file['fname1'];
                $absFilename = $absPath . $file['fname1'];
                $tFilename = "/" . $filename;

                $tFile = File::find()
                        ->where(['path' => $tFilename])
                        ->limit(1)
                        ->one();

                if ($mode == "empty")
                    $tFile = null;

                if (isset($tFile)) {
                    // есть такой путь в базе
                    if (file_exists($absFilename)) {
                        // и есть на диске
                        // тут ВОЗМОЖНО можно апдейтить)
                        echo '--------------' . $absFilename . ' already exists!' . "\n\r";
                        flush();
                    } else {
                        // есть в базе, но нет на диске - создаем файл
                        if (!is_dir($absPath)) {
                            mkdir($absPath);
                        }

                        LexemaHelper::base64ToFile($absFilename, $data);
                        echo '+++++++++++++' . $absFilename . ' created' . "\n\r";
                        flush();
                    }
                } else {
                    // если нет в базе
                    if (file_exists($absFilename)) {
                        // но есть на диске - добавляем запись в базу
                        echo $absFilename . ' exists. Adding to DB' . "\n\r";
                        flush();
                        $newFile = new File();
                        $newFile->type = $this->fileTypes[$type];
                        $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                        $newFile->relModelId = $id;
                        $newFile->path = $tFilename;
                        $newFile->status = File::FILE_PUBLISHED;
                        $newFile->save();
                    } else {
                        // нет ни в базе, ни на диске
                        if (!is_dir($absPath)) {
                            mkdir($absPath);
                        }

                        LexemaHelper::base64ToFile($absFilename, $data);

                        flush();
                        $newFile = new File();
                        $newFile->type = $this->fileTypes[$type];
                        $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                        $newFile->relModelId = $id;
                        $newFile->path = $tFilename;
                        $newFile->status = File::FILE_PUBLISHED;
                        $newFile->save();
                        echo '+++++++++++++' . $absFilename . ' created' . "\n\r";
                        flush();
                    }
                }
//                if ($tFile && file_exists($filename)) {
//                    // если есть в базе и на диске - обновляем
//                } elseif (!$tFile && file_exists($filename)) {
//                    // нет в базе, но есть на диске - пишем в базу
//                } elseif ($tFile && !file_exists($filename)) {
//                    // есть в базе, но нет на диске - создаем файл
//                } elseif (!$tFile && !file_exists($filename)) {
//                    // нет ни там, ни там - пишем в базу и создаем файл
//                }
            }
            // ***** прогресс
            $counter++;
            $currentPercent = $counter / $onePercent;
            echo "\r\r" . $guid . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
            flush();
        }
        echo 'File import done.';
//        file_put_contents('c:/lexemalogs/file_import_errors.txt', print_r($errorProducts, true) . "\n\r\n\r", FILE_APPEND);
    }

    /** Импорт файлов по конкретному продукту
     * @param $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importFileByGuid($guid) {
        $product = Product::findByGuid($guid, true);
        $id = $product->id;
        $path = "files/import/products/$id/";
        $absPath = \Yii::getAlias('@webroot') . "/files/import/products/$id/";
        $files = $this->connection->queryArray("ESI_GetProductImages&GlobalIdMaterial=$guid");

        if (!$files) {
            //echo 'К этому товару файлов нет.' . PHP_EOL;
        }

        foreach ($files as $file) {
            if (!isset($file['ftype'])) {
                continue;
            }
            $type = $file['ftype'];
            $data = $file['file1']; // base64 данные
            $filename = $path . $file['fname1'];
            $absFilename = $absPath . $file['fname1'];
            $tFilename = "/" . $filename;

            $tFile = File::find()
                    ->where(['path' => $tFilename])
                    ->limit(1)
                    ->one();

            if (isset($tFile)) {
                // есть такой путь в базе
                if (file_exists($absFilename)) {
                    // и есть на диске
                    // тут ВОЗМОЖНО можно апдейтить)
                    //echo "Файл \"" . $absFilename . "\" уже существует." . "\n\r";
                    flush();
                } else {
                    // есть в базе, но нет на диске - создаем файл
                    if (!is_dir($absPath)) {
                        mkdir($absPath);
                    }

                    LexemaHelper::base64ToFile($absFilename, $data);
                    //echo "Файл \"" . $absFilename . "\" добавлен." . "\n\r";
                    flush();
                }
            } else {
                // если нет в базе
                if (file_exists($absFilename)) {
                    // но есть на диске - добавляем запись в базу
                    //echo "Файл \"" . $absFilename . "\" есть на диске. Добавляем в БД." . "\n\r";
                    flush();
                    $newFile = new File();
                    $newFile->type = $this->fileTypes[$type];
                    $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                    $newFile->relModelId = $id;
                    $newFile->path = $tFilename;
                    $newFile->status = File::FILE_PUBLISHED;
                    $newFile->save();
                } else {
//                        echo 'нет ни в базе ни на диске'; exit;
                    // нет ни в базе, ни на диске
                    if (!is_dir($absPath)) {
                        mkdir($absPath);
                    }

                    LexemaHelper::base64ToFile($absFilename, $data);

                    flush();
                    $newFile = new File();
                    $newFile->type = $this->fileTypes[$type];
                    $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                    $newFile->relModelId = $id;
                    $newFile->path = $tFilename;
                    $newFile->status = File::FILE_PUBLISHED;
                    $newFile->save();
                    //echo "Файл \"" . $absFilename . "\" добавлен." . "\n\r";
                    flush();
                }
            }
        }

        return true;
    }

    /**
     * @param $source
     * @throws \ReflectionException
     */
    private function addAnalogue($source) {
        $parentGuid = $source['parentProductglobalId'];
        $analogueGuid = $source['ProductglobalId'];

        $parentProduct = Product::findByGuid($parentGuid, true);
        $analogueProduct = Product::findByGuid($analogueGuid, true);

        if ($parentProduct && $analogueProduct) {
            $tAnalogue = ProductAnalogue::find()
                    ->where(['productId' => $parentProduct->id])
                    ->andWhere(['productAnalogueId' => $analogueProduct->id])
                    ->limit(1)
                    ->one();

            if ($tAnalogue)
                return;

            try {
                $newAnalogue = new ProductAnalogue();
                $newAnalogue->productId = $parentProduct->id;
                $newAnalogue->productAnalogueId = $analogueProduct->id;
                $newAnalogue->save();

                echo "К товару \"" . $parentProduct->title . "\" добавлен аналог \"" .
                $analogueProduct->title . "\". \n\r";
            } catch (ErrorException $e) {
                echo $e->getMessage();
                return;
            }
        }
    }

    /**
     *  Импорт аналогов товара
     */
    public function importAnalogue($guid = null) {
        // для семафора
        if ($guid) {
            $analogue = $this->connection->queryArray("ESI_Analogue&ProductGlobalId=$guid");
            foreach ($analogue as $item) {
                $this->addAnalogue($item);
            }
            return true;
        }

        $analogues = $this->connection->queryArray('ESI_Analogue');

        foreach ($analogues as $analogue) {
            $this->addAnalogue($analogue);
        }

        echo 'Импорт аналогов завершен.';
    }

    /**
     * @param $source
     * @throws \ReflectionException
     */
    private function addAssociated($source) {
        $parentGuid = $source['parentProductglobalId'];
        $assocGuid = $source['ProductglobalId'];

        $parentProduct = Product::findByGuid($parentGuid, true);
        $assocProduct = Product::findByGuid($assocGuid, true);

        if ($parentProduct && $assocProduct) {
            $tAssoc = ProductAssociated::find()
                    ->where(['productId' => $parentProduct->id])
                    ->andWhere(['productAssociatedId' => $assocProduct->id])
                    ->limit(1)
                    ->one();
            if ($tAssoc)
                return;

            try {
                $newAssoc = new ProductAssociated();
                $newAssoc->productId = $parentProduct->id;
                $newAssoc->productAssociatedId = $assocProduct->id;
                $newAssoc->save();

                echo "К товару \"" . $parentProduct->title . "\" добавлен сопутствующий товар \"" .
                $assocProduct->title . "\". \n\r";
            } catch (ErrorException $e) {
                echo $e->getMessage();
                return;
            }
        }
    }

    /** Импорт сопутствующих товаров
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importAssociated($guid = null) {
        if ($guid) {
            $associated = $this->connection->queryArray("ESI_TovarSoput&ProductGlobalId=$guid");
            foreach ($associated as $item) {
                $this->addAssociated($item);
            }
            return true;
        }

        $associated = $this->connection->queryArray('ESI_TovarSoput');

        foreach ($associated as $item) {
            $this->addAssociated($item);
        }
        echo 'Импорт сопутствующих товаров завершен.';
    }

    /** Импорт/обновление остатков на складах по конкретному товару
     * @param $guid
     * @return bool|void
     * @throws \ReflectionException
     */
    public function importBalanceByGuid($guid) {
        $dbProduct = Product::findByGuid($guid, true);

        if (!$dbProduct) {
            echo "Нет такого продукта." . PHP_EOL;
            return;
        }

        if (!$this->balances) {
            $_storages = $this->connection->queryArray('ESI_GetChangeStorage&Mode=0');

            $balances = [];
            foreach ($_storages as $item) {
                $balances[$item['materialGlobalId']][] = $item;
            }

            unset($_storages);
            $this->balances = $balances;
        } else {
            $balances = $this->balances;
        }

        if (isset($balances[$guid])) {
            $balance = $balances[$guid];
            foreach ($balance as $value) {
                $dbStorage = Storage::findByGuid($value['StorageGlobalId'], true);

                if (!$dbStorage) {
                    echo "Нет склада: " . $value['StorageGlobalId'] . PHP_EOL;
                    continue;
                }

                $dbBalance = StorageBalance::find()
                        ->where(['storageId' => $dbStorage->id])
                        ->andWhere(['productId' => $dbProduct->id])
                        ->limit(1)
                        ->one();

                if ($dbBalance) {
                    // update
                    $quantity = number_format($value['Ost'], 3, ".", "");

                    if ($dbBalance->quantity != $quantity) {
                        if ($quantity > 0) {
                            $dbBalance->state = StorageBalance::STATE_IN_STOCK;
                        } else {
                            $dbBalance->state = StorageBalance::STATE_WAIT;
                        }
                        $tQuantity = $dbBalance->quantity;
                        $dbBalance->quantity = $quantity;
                        $dbBalance->save();
                        echo $dbProduct->title . ' updated (from: ' . $tQuantity . ' to: ' . $quantity . ')' . "\n\r";
                    }
                } else {
                    $newBalance = new StorageBalance();
                    $newBalance->storageId = $dbStorage->id;
                    $newBalance->productId = $dbProduct->id;
                    $newBalance->quantity = $value['Ost'];
                    $newBalance->state = StorageBalance::STATE_IN_STOCK;
                    $newBalance->dtReceipt = date("Y-m-d H:i:s");
                    $newBalance->save();

                    //echo $product->title . " added($quantity)" . "\n\r";
                }
            }
        }

        return true;

        /**
         *  $balance - массив вида:
         *  [ГУИД_ПРОДУКТА] => [
         *          [ГУИД_СКЛАДА] => "Остаток",
         *          ...
         *      ]
         */
        foreach ($balances as $stGuid => $quantity) {
            $storage = Storage::findByGuid($stGuid, true);

            if (!$storage) {
                echo "Нет такого склада. \n\r";
                continue;
            }

            $dbBalance = StorageBalance::find()
                    ->where(['storageId' => $storage->id])
                    ->andWhere(['productId' => $dbProduct->id])
                    ->limit(1)
                    ->one();

            if ($dbBalance) {
                // update
                $quantity = number_format($quantity, 3, ".", "");

                if ($dbBalance->quantity != $quantity) {
                    if ($quantity > 0)
                        $dbBalance->state = StorageBalance::STATE_IN_STOCK;
                    else
                        $dbBalance->state = StorageBalance::STATE_WAIT;
                    $tQuantity = $dbBalance->quantity;
                    $dbBalance->quantity = $quantity;
                    $dbBalance->save();
                    echo $dbProduct->title . ' updated (from: ' . $tQuantity . ' to: ' . $quantity . ')' . "\n\r";
                }
            } else {
                $newBalance = new StorageBalance();
                $newBalance->storageId = $storage->id;
                $newBalance->productId = $dbProduct->id;
                $newBalance->quantity = $quantity;
                $newBalance->state = StorageBalance::STATE_IN_STOCK;
                $newBalance->dtReceipt = date("Y-m-d H:i:s");
                $newBalance->save();

                //echo $product->title . " added($quantity)" . "\n\r";
            }
        }
        return true;
    }

    /**
     *  Импорт остатков по складам
     */
    public function importStorageBalance($data = null) {
        if (!$data) {
            $_storages = $this->connection->request('ESI_GetChangeStorage&Mode=0');
        } else {
            $_storages = $data;
        }

        $storages = [];

        foreach ($_storages as $item) {
            $stGuid = $item['StorageGlobalId'];
            $stProd = $item['materialGlobalId'];
            $ost = $item['Ost'];
            if (isset($storages[$stGuid][$stProd]))
                continue;
            $storages[$stGuid][$stProd] = $ost;
        }

        /**
         *  $storages - массив вида:
         *  [ГУИД_СКЛАДА] => [
         *          [ГУИД_ПРОДУКТА] => "Остаток",
         *          ...
         *      ]
         */
        unset($_storages);

        // progress
        $totalCount = count($storages);
        foreach ($storages as $storage) {
            $totalCount += count($storage);
        }
        $onePercent = intval(ceil($totalCount / 100));
        $currentPercent = 0;
        $counter = 0;

        // import storage balances
        foreach ($storages as $storageGuid => $storage) {
            $tStorage = OuterRel::find()
                    ->where(['guid' => $storageGuid])
                    ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_STORAGE])
                    ->limit(1)
                    ->one();
            @$tStorageId = $tStorage->storage->id;

            foreach ($storage as $productGuid => $quantity) {
                $tProd = OuterRel::find()
                        ->where(['guid' => $productGuid])
                        ->andWhere(['relModel' => ModelRelationHelper::REL_MODEL_PRODUCT])
                        ->one();
                @$tProdId = $tProd->product->id;

                if ($tStorageId && $tProdId) {
                    $tBalance = StorageBalance::find()
                            ->where(['storageId' => $tStorageId])
                            ->andWhere(['productId' => $tProdId])
                            ->limit(1)
                            ->one();

                    if ($tBalance) {
                        // апдейт остатка
                        $quantity = number_format($quantity, 3, ".", "");
                        if ($tBalance->quantity == $quantity) {
                            $counter++;
                            continue;
                        }
                        if ($quantity > 0)
                            $tBalance->state = StorageBalance::STATE_IN_STOCK;
                        $tQuantity = $tBalance->quantity;
                        $tBalance->quantity = $quantity;
                        $tBalance->save();
                        echo $tProd->product->title . ' updated (from: ' . $tQuantity . ' to: ' . $quantity . ')' . "\n\r";
                    } else {
                        $newBalance = new StorageBalance();
                        $newBalance->storageId = $tStorageId;
                        $newBalance->productId = $tProdId;
                        $newBalance->quantity = $quantity;
                        $newBalance->state = StorageBalance::STATE_IN_STOCK;
                        $newBalance->dtReceipt = date("Y-m-d H:i:s");
                        $newBalance->save();

                        echo $tProd->product->title . " added($quantity)" . "\n\r";
                    }
                }
                $counter++;
                $currentPercent = $counter / $onePercent;
                echo "\r\r" . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
                flush();
            }
            $counter++;
            $currentPercent = $counter / $onePercent;
            echo "\r\r" . ' | Progress >>> ' . round($currentPercent, 2) . '% ' . "\r";
            flush();
        }

        if (!$data) {
            echo "Импорт остатков на складах завершен. \n\r";
        }
    }

    /** Добавление склада
     * @param $source
     * @return bool
     * @throws Exception
     * @throws \ReflectionException
     */
    private function addStorage($source) {
        if (!$source['globalId'] || !$source['ParentGlobalIDShop']) {
            return false;
        }
        $tStorage = Storage::findByGuid($source['globalId']);

        if ($tStorage->isNewRecord == false) {
            $tShop = Storage::findByGuid($source['ParentGlobalIDShop']);
            if (!$tShop)
                return false;

            if (!isset($tShop->address)) {
                throw new Exception('У магазина этого склада нет адреса в БД. Перезапустите импорт магазинов');
            }

            // upd current storages
            $tStorage->title = $source['name'];
            $tStorage->type = Storage::TYPE_STORAGE;
            $tStorage->status = Storage::STATUS_ACTIVE;
            $tStorage->save();

            // address
            $tAddress = Address::find()
                    ->where(['relModel' => ModelRelationHelper::REL_MODEL_STORAGE])
                    ->andWhere(['relModelId' => $tStorage->id])
                    ->limit(1)
                    ->one();

            if (!$tAddress) {
                $attrs = $tShop->address->attributes;
                $attrs['id'] = null;
                $newAddress = new Address();
                $newAddress->setAttributes($attrs);
                $newAddress->relModelId = $tStorage->id;
                $newAddress->save();
            }

            echo "Склад \"" . $source['name'] . "\" уже существует(обновлен). \n\r";
        } else {
            $tShop = Storage::findByGuid($source['ParentGlobalIDShop']);
            if (!$tShop)
                return false;

            if (!isset($tShop->address)) {
                throw new Exception('У магазина этого склада нет адреса в БД. Перезапустите импорт магазинов');
            }

            // add new storages
            $tStorage = new Storage();
            $tStorage->title = $source['name'];
            $tStorage->type = Storage::TYPE_STORAGE;
            $tStorage->status = Storage::STATUS_ACTIVE;
            $tStorage->save();

            // address
            $attrs = $tShop->address->attributes;
            $attrs['id'] = null;
            $newAddress = new Address();
            $newAddress->setAttributes($attrs);
            $newAddress->relModelId = $tStorage->id;
            $newAddress->save();

            echo "Склад \"" . $source['name'] . "\" добавлен. \n\r";
        }

        return true;
    }

    /** Импорт складов
     * @param null $guid
     * @return bool
     * @throws Exception
     * @throws \ReflectionException
     */
    public function importStorage($guid = null) {
        // TODO добавился новый столбец в storage - isNew
        $storages = $this->connection->queryArray('ESI_GetStorage');

        // для семафора
        if ($guid) {
            foreach ($storages as $storage) {
                if ($storage['globalId'] == $guid) {
                    $result = $this->addStorage($storage);
                    return $result;
                }
            }
        }

        foreach ($storages as $storage) {
            $this->addStorage($storage);
        }

        echo "Импорт складов завершен.\r\n";
    }

    /** Добавление магазина
     * @param $source
     * @return bool
     * @throws \ReflectionException
     */
    public function addShop($source) {
        $tShop = Storage::findByGuid($source['globalId']);
        if (!isset($source['CityName'])) {
            return false;
        }
        $tCity = City::find()->where(['title' => $source['CityName']])->one();

        if ($tShop->isNewRecord == false) {
            // upd current shop
            $phone = trim(str_replace("тел.", "", $source['phone']));
            $tShop->title = $source['name'];
            $tShop->phone = $phone;
            $tShop->type = Storage::TYPE_SHOP;
            $tShop->status = Storage::STATUS_ACTIVE;
            $tShop->cityId = $tCity->id;
            if (isset($source['PrimaryStorage']) && $source['PrimaryStorage'] == 1) {
                $tShop->isMain = 1;
            }
            $tShop->save();

            // upd address
            $tAddress = Address::find()
                    ->where(['relModel' => ModelRelationHelper::REL_MODEL_STORAGE])
                    ->andWhere(['relModelId' => $tShop->id])
                    ->limit(1)
                    ->one();

            if (!$tAddress) {
                $address = explode(",", $source['adress']);
                array_shift($address);
                $address = implode(",", $address);
                $tAddress = new Address();
                $tAddress->countryId = $tCity->countryId;
                $tAddress->regionId = $tCity->regionId;
                $tAddress->cityId = $tCity->id;
                $tAddress->address = $address;
                $tAddress->relModel = ModelRelationHelper::REL_MODEL_STORAGE;
                $tAddress->relModelId = $tShop->id;
                $tAddress->save();
            }

            echo "Магазин \"" . $source['name'] . "\" уже существует(обновлен). \n\r";
        } else {
            // add new shop
            $phone = trim(str_replace("тел.", "", $source['phone']));
            $tShop->title = $source['name'];
            $tShop->phone = $phone;
            $tShop->type = Storage::TYPE_SHOP;
            $tShop->status = Storage::STATUS_ACTIVE;
            $tShop->cityId = $tCity->id;
            if (isset($source['PrimaryStorage']) && $source['PrimaryStorage'] == 1) {
                $tShop->isMain = 1;
            }
            $tShop->save();

            // add address
            $address = explode(",", $source['adress']);
            array_shift($address);
            $address = implode(",", $address);
            $tCity = City::find()->where(['title' => $source['CityName']])->one();
            $tAddress = new Address();
            $tAddress->countryId = $tCity->countryId;
            $tAddress->regionId = $tCity->regionId;
            $tAddress->cityId = $tCity->id;
            $tAddress->address = $address;
            $tAddress->relModel = ModelRelationHelper::REL_MODEL_STORAGE;
            $tAddress->relModelId = $tShop->id;
            $tAddress->save();

            echo "Магазин \"" . $source['name'] . "\" добавлен. \n\r";
        }

        return true;
    }

    /** Импорт магазинов и обновление списка городов на сайте
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importShop($guid = null) {
        $shops = $this->connection->queryArray('ESI_GetShop');

        // для семафора
        if ($guid) {
            foreach ($shops as $shop) {
                if ($shop['globalId'] == $guid) {
                    $result = $this->addShop($shop);
                    return $result;
                }
            }
        }

        foreach ($shops as $shop) {
            $this->addShop($shop);
        }

        $this->refreshCities();

        echo "Импорт магазинов завершен.\r\n";
    }

    /**
     *  Обновление списка городов на сайте, на основе имеющихся магазинов
     */
    public function refreshCities() {
        $cities = Storage::find()
                ->select('cityId')
                ->distinct()
                ->where(['type' => Storage::TYPE_SHOP])
                ->andWhere('cityId IS NOT NULL')
                ->all();

        CityOnSite::deleteAll();

        foreach ($cities as $city) {
            $tCity = new CityOnSite();
            $tCity->cityId = $city->cityId;
            $tCity->save();
        }

        $anotherCity = City::find()->where(['title' => 'Другой город'])->limit(1)->one();
        $newAnotherCity = new CityOnSite();
        $newAnotherCity->cityId = $anotherCity->id;
        $newAnotherCity->save();
    }

    /**
     * @param $source
     * @return bool
     * @throws \ReflectionException
     */
    private function addClient($source) {
        $tClient = Client::findByGuid($source['ContractorGlobalId'], true);
        if (isset($source['typezena'])) {
            $priceGroup = ProductPriceGroup::findByGuid($source['typezena'], true);
        } else {
            $priceGroup = null;
        }

        $params = new \stdClass();
        $params->inn = $source['Inn'];
        $params->kpp = $source['Kpp'];
        $params->postAddress = $source['PostAdress'];
        $params->lawAddress = $source['LawAdress'];
        $params->payment = $source['PlatRekvizit'];
        $params->balance = $source['balance'] ?? "98000";
        $params->paymentSumm = $source['Summa'];
        $params->lastPaymentSumm = $source['SummaLastPlat'];
        $params->lastPaymentDate = $source['DateLastPlat'];
        $params->postponement = $source['daysofdelay'];
        $params->creditLimit = $source['SummaKredLimit'];
        $params->manager = $source['manager'];
        $params->managerEmail = $source['manageremail'];
        $params->managerPhone = $source['managerphone'];
        $params->specialPricePermission = $source['ResolOnSpecZena'];
        $params = Json::encode($params);

        if ($tClient) {
            if ($priceGroup) {
                $pgToClient = ProductPriceGroupToClient::find()
                        ->where(['clientId' => $tClient->id])
                        ->limit(1)
                        ->one();
                if (!$pgToClient) {
                    $tClient->link('priceGroup', $priceGroup);
                } else {
                    if (!$pgToClient->productPriceGroupId == $priceGroup->id) {
                        $pgToClient->productPriceGroupId = $priceGroup->id;
                        $pgToClient->save();
                    }
                }
            }

            // уже есть такой
            if ($tClient->params != Json::decode($params)) {
                // не совпадают - апдейт
                $tClient->params = $params;
                $tClient->save();
            }

            echo "Клиент \"" . $source['Name'] . "\" уже существует(обновлен)\n\r";
        } else {
            $user = User::findByGuid($source['ContractorGlobalId'], true);

            // добавляем клиента
            $newClient = new Client();
            $newClient->title = $source['Name'];
            $newClient->type = Client::TYPE_ENTITY;
            $newClient->status = Client::STATUS_ACTIVE;
            $newClient->phone = $source['phone'];
            $newClient->email = $source['email'];
            $newClient->params = $params;
            $newClient->save();

            // user_to_client
            if ($user) {
                UserToClient::bind($user, $newClient);
            }

            // product_price_group_to_client
            if ($priceGroup) {
                $newClient->link('priceGroup', $priceGroup);
            }

            // outer_rel
            $newClientRel = new OuterRel();
            $newClientRel->guid = $source['ContractorGlobalId'];
            $newClientRel->relModel = ModelRelationHelper::REL_MODEL_CLIENT;
            $newClientRel->relModelId = $newClient->id;
            $newClientRel->save();

            echo "Клиент: " . $source['Name'] . " добавлен\n\r";
        }

        return true;
    }

    /** Импорт клиентов
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importClient($guid = null) {
        $clients = $this->connection->queryArray('ESI_GetContractor');

        // для семафора
        if ($guid) {
            foreach ($clients as $client) {
                if ($client['ContractorGlobalId'] == $guid) {
                    $result = $this->addClient($client);
                    return $result;
                }
            }
        }

        foreach ($clients as $client) {
            $this->addClient($client);
        }

        echo "Импорт клиентов завершен.\n\r";
    }

    /**
     * @param $source
     * @return bool
     * @throws \ReflectionException
     */
    private function addUser($source) {
        $tUser = User::findByGuid($source['ContractorGlobalId'], true);

        if ($tUser) {
            // есть такой
            echo "Пользователь \"" . $source['LoginName'] . "\" уже существует(обновлен)\n\r";
        } else {
            $fio = $source['Fam'] . " " . $source['Im'] . " " . $source['Otch'];

            // добавляем юзера
            $newUser = new User();
            //$newUser->load($$source)
            $newUser->username = $source['LoginName'];
            $newUser->email = $source['eMail'];
            $newUser->phone = $source['Phone'];
            $newUser->fio = $fio;
            $newUser->fromRemote = true;
            $newUser->status = User::STATUS_ACTIVE;
            $newUser->fromRemote = true;
            $newUser->save(false);

            // связь
            $newUserRel = new OuterRel();
            $newUserRel->guid = $source['ContractorGlobalId'];
            $newUserRel->relModel = ModelRelationHelper::REL_MODEL_USER;
            $newUserRel->relModelId = $newUser->id;
            $newUserRel->save();
            echo "Пользователь \"" . $source['LoginName'] . "\" добавлен\n\r";
        }

        return true;
    }

    /** Импорт пользователей
     * @param null $guid
     * @return bool
     * @throws \ReflectionException
     */
    public function importUser($guid = null) {
        $users = $this->connection->queryArray('ESI_GetLoggin');

        // для семаформа
        if ($guid) {
            foreach ($users as $user) {
                if ($user['ContractorGlobalId'] == $guid) {
                    $result = $this->addUser($user);
                    return $result;
                }
            }
        }

        foreach ($users as $user) {
            $this->addUser($user);
        }

        echo "Импорт пользователей завершен.\n\r";
    }

    /** Импорт контрактов
     * @throws \ReflectionException
     */
    public function importContract() {
        $clients = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
                ->all();

        foreach ($clients as $client) {
            $result = $this->connection->queryArray("ESI_GetReestrContract&Contractor=$client->guid");

            foreach ($result as $contract) {
                if (!$contract['ContractId'])
                    continue;
                $tContract = Contract::findByGuid($contract['ContractId']);

                if ($tContract->isNewRecord == false) {
                    // есть такой контракт в бд
                    echo "Контракт № \"" . $contract['Nomer'] . "\" для клиента \"" . $client->client->title . "\" уже существует. \n\r";
                    // проверка файлов
                    continue;
                }

                if (isset($contract['BegDate']['$value'])) {
                    $beginDate = LexemaHelper::dateConvert($contract['BegDate']['$value']);
                } else
                    $beginDate = null;

                if (isset($contract['EndDate']['$value'])) {
                    $endDate = LexemaHelper::dateConvert($contract['EndDate']['$value']);
                } else
                    $endDate = null;

                $tContract->number = $contract['Nomer'];
                $tContract->dtStart = $beginDate;
                $tContract->dtEnd = $endDate;
                $tContract->clientId = $client->client->id;
                $tContract->status = Contract::STATUS_NOT_ACTIVE;
                $tContract->save();

                echo "Контракт № \"" . $contract['Nomer'] . "\" для клиента \"" . $client->client->title . "\" добавлен. \n\r";
            }
        }
        echo "Импорт контрактов завершен.";
    }

    /**
     *  Импорт файлов, содержащихся в договорах клиентов
     */
    public function importClientFile() {
        $clients = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
                ->all();

        foreach ($clients as $client) {
            $result = $this->connection->queryArray("ESI_GetReestrContract&Contractor=$client->guid");
            $clientId = $client->client->id;

            foreach ($result as $item) {
                $vcode = $item['vcode'];
                $tdoc = $item['tdoc'];
                $result = $this->connection->queryArray("ESI_GetDocumentFiles&Vcode=$vcode&Tdoc=$tdoc");

                foreach ($result as $item) {
                    $filename = $item['fname1'];
                    $fileData = $item['file1'];

                    $filePath = "/files/import/clients/$clientId/";
                    $tFilePath = $filePath . $filename;
                    $absPath = \Yii::getAlias("@webroot") . $filePath;
                    $absFPath = \Yii::getAlias("@webroot") . $filePath . $filename;

                    if (!is_dir($absPath)) {
                        mkdir($absPath, 0777, true);
                    }

                    if (!file_exists($absFPath)) {
                        // нет файла на диске
                        LexemaHelper::base64ToFile($absFPath, $fileData);
                    }

                    $tFile = File::find()
                            ->where(['path' => $tFilePath])
                            ->limit(1)
                            ->one();

                    if (!$tFile) {
                        // в бд нет записи
                        $file = new File();
                        $file->path = $tFilePath;
                        $file->relModel = ModelRelationHelper::REL_MODEL_CLIENT;
                        $file->relModelId = $client->client->id;
                        $file->type = File::TYPE_DOCUMENT;
                        $file->status = File::FILE_PUBLISHED;
                        $file->save();
                        echo "Файл добавлен. \n\r";
                    }
                }
            }
        }
        echo "Импорт файлов клиентов завершен. \n\r";
    }

    /** Импорт производителей(брендов)
     * @throws \Exception
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function importManufacturer() {
        $result = $this->connection->queryArray("ESI_GetProduceName");

        $files = File::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_MANUFACTURER])
                ->orderBy('relModelId ASC')
                ->all();

        foreach ($files as $file) {
            $file->delete();
        }

        foreach ($result as $manufacturer) {
            $tManufaturer = Manufacturer::findByGuid($manufacturer['GlobalBrendId']);

            if ($tManufaturer->isNewRecord == false) {
                // есть такой
                // апдейт слагов
                $slug = str_replace(" ", "_", $tManufaturer->title);
                $slug = mb_ereg_replace("[^А-Яа-яA-Za-z0-9_\.\-]", "", $slug);
                $tManufaturer->slug = $slug;
                $tManufaturer->save();

                // лого
                $filename = "logo.jpg";
                $fileData = $manufacturer['file1'];

                if (!$fileData) {
                    continue;
                }

                $filePath = "/files/import/brands/{$tManufaturer->id}/";
                $tFilePath = $filePath . $filename;
                $absPath = \Yii::getAlias("@webroot") . $filePath;
                $absFPath = \Yii::getAlias("@webroot") . $filePath . $filename;

                if (!is_dir($absPath)) {
                    mkdir($absPath, 0777, true);
                }

                if (!file_exists($absFPath)) {
                    // нет файла на диске
                    LexemaHelper::base64ToFile($absFPath, $fileData);
                }

                $tFile = File::find()
                        ->where(['path' => $tFilePath])
                        ->limit(1)
                        ->one();

                if (!$tFile) {
                    $file = new File();
                    $file->path = $tFilePath;
                    $file->relModel = ModelRelationHelper::REL_MODEL_MANUFACTURER;
                    $file->relModelId = $tManufaturer->id;
                    $file->type = File::TYPE_IMAGE;
                    $file->status = File::FILE_PUBLISHED;
                    $file->save();
                }
            } else {
                // добавление нового
                $tManufaturer->title = $manufacturer['BrandName'];
                $slug = str_replace(" ", "_", $manufacturer['BrandName']);
                $slug = mb_ereg_replace("[^А-Яа-яA-Za-z0-9_\.\-]", "", $slug);
                $tManufaturer->slug = $slug;
                $tManufaturer->save();

                // лого
                $filename = "logo.jpg";
                $fileData = $manufacturer['file1'];

                if (!$fileData) {
                    continue;
                }

                $filePath = "/files/import/brands/{$tManufaturer->id}/";
                $tFilePath = $filePath . $filename;
                $absPath = \Yii::getAlias("@webroot") . $filePath;
                $absFPath = \Yii::getAlias("@webroot") . $filePath . $filename;

                if (!is_dir($absPath)) {
                    mkdir($absPath, 0777, true);
                }

                if (!file_exists($absFPath)) {
                    // нет файла на диске
                    LexemaHelper::base64ToFile($absFPath, $fileData);
                }

                $tFile = File::find()
                        ->where(['path' => $tFilePath])
                        ->limit(1)
                        ->one();

                if (!$tFile) {
                    $file = new File();
                    $file->path = $tFilePath;
                    $file->relModel = ModelRelationHelper::REL_MODEL_MANUFACTURER;
                    $file->relModelId = $tManufaturer->id;
                    $file->type = File::TYPE_IMAGE;
                    $file->status = File::FILE_PUBLISHED;
                    $file->save();
                }

                echo "Производитель " . $manufacturer['BrandName'] . " добавлен. \n\r";
            }
        }
        echo "Импорт производителей завершен.\n\r";
    }

    /** Возврашает список док-ов конкретного клиента
     * @param $clientId - guid клиента
     * @return array
     */
    public function getDocumentList($clientId) {
        $result = $this->connection->queryArray("ESI_GetPdocDocument&Contractor=$clientId");

        $data = [];
        foreach ($result as $key => $value) {
            $data[$value['vcode']] = $value;
        }

        return $data;
    }

    // TODO пока с ним не понятно
    public function importDocuments() {
        $clients = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
                ->all();

        foreach ($clients as $client) {
            $docs = $this->getDocumentList($client->guid);
            dump($docs);
        }
    }

    /** Возвращает список товаров, кол-во и цены по Vcode конкретного документа
     * @param $code - Vcode докумена
     * @param bool $raw - сырые данные
     * @return array
     * @throws \ReflectionException
     */
    public function getDocumentDetails($code, $raw = false) {
        $result = $this->connection->queryArray("ESI_GetPdocmatDocument&Vcode=$code");

        if ($raw === true)
            return $result;

        $data = [];

        foreach ($result as $key => $value) {
            if (!$value['materialGlobalId'])
                continue;
            //$data[] = $value;
            $product = Product::findByGuid($value['materialGlobalId'], true);
            $unit = Unit::findByGuid($value['edizmGlobalId'], true);
            $count = $value['kolvo'] . " " . $unit->title;
            if ($product) {
                $data[$key]['materialGlobalId'] = $product->title ?? "";
                $data[$key]['edizmGlobalId'] = $unit->title;
                $data[$key]['count'] = $count;
                $data[$key]['ozena2'] = $value['ozena2'];
                $data[$key]['sumBndsRSH'] = $value['sumBndsRSH'];
                $data[$key]['sumndsRSH'] = $value['sumndsRSH'];
                $data[$key]['sumSndsRSH'] = $value['sumSndsRSH'];
            }
        }

        //dump($data);exit;

        return $data;
        //dump($result);
    }

    // TODO пока не трогать
    public function getOrders($clientId) {
        $client = Client::findByGuid($clientId, true);

        $result = $this->connection->queryArray("ESI_GetReestrEZK&GlobalIdDeban=$clientId");
        dump($result);
    }

    public function updateOrder($vcode) {
        $apiOrders = $this->connection->queryArray('ESI_GetReestrEZK');

        $foundOrder = null;
        foreach ($apiOrders as $apiOrder) {
            if ($apiOrder['vcode'] == $vcode) {
                $foundOrder = $apiOrder;
            }
        }

        if ($foundOrder) {
            $dbLexemaOrder = LexemaOrder::find()
                    ->where(['orderNumber' => $foundOrder['OrderId']])
                    ->with('order')
                    ->limit(1)
                    ->one();

            if ($dbLexemaOrder) {
                $dbOrder = $dbLexemaOrder->order ?? null;
                $dbContent = $dbOrder->content ?? null;
            }

            $apiContent = $this->connection->queryArray("ESI_GetPdocmatDocument&Vcode=" . $foundOrder['vcode']);

            if (isset($dbOrder) && isset($dbContent)) {
                $result = LexemaHelper::compareOrderContent($dbOrder->id, $apiContent, $dbContent);
                if ($result) {
                    echo "Заказ № " . $dbLexemaOrder->orderNumber . " обновлен" . PHP_EOL;
                    return true;
                }
            }

            return false;
        }
    }

    /**
     * Возвращает возможный ID для заказа
     * @param $id
     * @return int
     * @throws \Exception
     */
    public function validateOrderIndex($id) {
        $apiOrders = $this->connection->queryArray('ESI_GetReestrEZK');

        $ids = [];
        foreach ($apiOrders as $apiOrder) {
            $ids[] = str_replace('A_', '', $apiOrder['OrderId']);
        }

        sort($ids);
        $last = end($ids);
        $availableIndex = (int) $last + 1;

        return $availableIndex;
    }

    /**
     * @return bool
     * @throws \Exception
     * @deprecated
     */
    public function updateOrder1() {
        $apiOrders = $this->connection->queryArray("ESI_GetReestrEZK");

        foreach ($apiOrders as $apiOrder) {
            //$dbLexemaOrder = LexemaOrder::findOne(['orderNumber' => $order['OrderId']]);
            $dbLexemaOrder = LexemaOrder::find()
                    ->where(['orderNumber' => $apiOrder['OrderId']])
                    ->with('order')
                    ->limit(1)
                    ->one();

            if ($dbLexemaOrder) {
                $dbOrder = $dbLexemaOrder->order;
                $dbContent = $dbOrder->content;
                $apiContent = $this->connection->queryArray("ESI_GetPdocmatDocument&Vcode=" . $apiOrder['vcode']);

                if (!$apiContent || isset($apiContent['exception'])) {
                    continue;
                }

                if (isset($dbLexemaOrder->order) && isset($dbLexemaOrder->order->content)) {
                    $result = LexemaHelper::compareOrderContent($dbLexemaOrder->order->id, $apiContent, $dbContent);
                    if ($result) {
                        return true;
                    }
                }
            }
        }
    }

    /** Импорт заказов
     * @throws Exception
     * @throws \ReflectionException
     */
    public function importOrder() {
        $clients = OuterRel::find()
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
                ->all();

        $orders = $this->connection->queryArray("ESI_GetReestrEZK");
        // A9B5569D-71F3-4B40-AFD8-D8031ABE82B2 - гуид клиента(проставляется, когда в заказе нет клиента)

        foreach ($clients as $client) {
            $clientOrders = $this->connection->queryArray("ESI_GetReestrEZK&GlobalIdDeban=$client->guid");
            $clientDocs = $this->getDocumentList($client->guid);
            foreach ($clientOrders as $order) {
                $vcode = $order['vcode'];
                $doc = $clientDocs[$vcode];
                $lexemaOrder = LexemaOrder::find()
                        ->where(['orderNumber' => $doc['Nomer']])
                        ->limit(1)
                        ->one();

                if (!$lexemaOrder) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    $tOrder = new Order();
                    $tOrder->fromRemote = true;
                    $tOrder->detachBehaviors();
                    $tOrder->clientId = $client->relModelId;
                    $tOrder->userId = $client->client->userToClient;

                    $user = User::find()->where(['username' => $order['Login']])->limit(1)->one();

                    $tOrder->userId = isset($user) ? $user->id : null;

                    // обработка лексемовской даты (у них январь - 0)
                    $tOrder->dtCreate = LexemaHelper::dateConvert($doc['Rdate']['$value']);

                    $tOrder->source = Order::SOURCE_LEXEMA;
                    $tOrder->save();

                    // Добавляем в order_lexema

                    $tOrderLexema = new LexemaOrder();
                    $tOrderLexema->orderNumber = $doc['Nomer'];
                    $tOrderLexema->orderId = $tOrder->id;
                    $tOrderLexema->save();

                    // Добавляем товары заказа в order_content

                    $orderContent = $this->getDocumentDetails($vcode, true);

                    if (!$orderContent) {
                        if ($tOrder->save() && $tOrderLexema->save()) {
                            $transaction->commit();
                            echo "Заказ № " . $doc['Nomer'] . " клиента \"" . $client->client->title . "\" добавлен. \r\n";
                        }
                    } else {
                        foreach ($orderContent as $item) {
                            $product = Product::findByGuid($item['materialGlobalId'], true);

                            if (!$product) {
                                $transaction->rollBack();
                                echo "Продукт с id " . $item['materialGlobalId'] . " у заказа №" . $doc['Nomer'] .
                                " отсутствует в БД.\r\n";
                                continue;
                            }

                            $tOrderContent = new OrderContent();
                            $tOrderContent->fromRemote = true;
                            $tOrderContent->priceValue = $item['ozena2'];
                            $tOrderContent->orderId = $tOrder->id;
                            $tOrderContent->productId = $product->id;
                            $tOrderContent->count = $item['kolvo'];
                            $tOrderContent->save();

                            if ($tOrder->save() && $tOrderLexema->save() && $tOrderContent->save()) {
                                continue;
                            } else {
                                $transaction->rollBack();
                                echo "что-то пошло не так " . $doc['Nomer'];
                                break;
                            }
                        }
                        $transaction->commit();
                        echo "Заказ № " . $doc['Nomer'] . " клиента \"" . $client->client->title . "\" добавлен. \r\n";
                    }
                }
            }
        }
        echo "Импорт заказов завершен.";
    }

    public function addSales($source) {
        $dbProduct = Product::findByGuid($source['TovarglobalId'], true);

        if (!$dbProduct) {
            echo "Такого продукта (" . $source['TovarglobalId'] . ") нет в базе. Возможно надо переимпортировать.<br>" . PHP_EOL;
            return false;
        }

        $salesCategoryId = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
        $salesCategory = ProductCategory::findOne($salesCategoryId);

        $checkLink = ProductToCategory::find()
                ->where(['productId' => $dbProduct->id])
                ->andWhere(['categoryId' => $salesCategory->id])
                ->limit(1)
                ->one();

        if (!$checkLink) {
            $dbProduct->link('categories', $salesCategory);
            echo 'Товар ' . $dbProduct->title . " добавлен в категорию скидочных товаров.<br>" . PHP_EOL;
            return true;
        } else {
            echo 'Товар ' . $dbProduct->title . " уже в категории скидочных товаров.<br>" . PHP_EOL;
        }
    }

    /** Импорт скидочных товаров
     * @throws \ReflectionException
     * @throws \yii\base\Exception
     */
    public function importSales($guid = null) {
        $sales = $this->connection->queryArray("ESI_GetSalesProduct");

        if ($guid) {
            foreach ($sales as $sale) {
                if ($sale['TovarglobalId'] == $guid) {
                    $result = $sale;
                }
            }

            if (isset($result)) {
                $this->addSales($result);
            } else {
                // TODO возможно тут надо убрать скидочный товар
            }
            return true;
        }

        foreach ($sales as $product) {
            $this->addSales($product);
        }

        return true;
    }

    /** Получение акта сверки (для API сайта)
     * @param $clientGuid
     * @param $from
     * @param $to
     * @return array|bool|int|string
     * @throws \ReflectionException
     */
    public function getReconciliationAct($clientGuid, $from, $to) {
        /** @var Client $client */
        $client = Client::findByGuid($clientGuid, true);

        if (!$client)
            return false;

        if (!isset($this->dataArray)) {
            $result = ReconciliationRepository::get()
                    ->findByContractorGuid($clientGuid)
                    ->all();

            //$result = $this->connection->queryArray("ESI_ReconciliationAct&Contractor=$clientGuid");
            $this->dataArray = $result;
        } else {
            $result = $this->dataArray;
        }

        // Перевод формата даты из:
        // 01.04.2018
        // в:
        // 2018.3.1
        $dateFrom = date_create($from)->format('Y.n.j');
        $dateFrom = LexemaHelper::dateConvert($dateFrom, false);
        $dateTo = date_create($to)->format('Y.n.j');
        $dateTo = LexemaHelper::dateConvert($dateTo, false);

        foreach ($result as $act) {
            if (($act['date1']['$value'] == $dateFrom) && ($act['date2']['$value'] == $dateTo)) {
                if (isset($act['FileDoc'])) {
                    // если файл готов
                    $fileData = $act['FileDoc'];
                    $filename = $from . "-" . $to . ".pdf";
                    $filePath = "/files/import/clients/$client->id/acts/";
                    $tFilePath = $filePath . $filename;
                    $absPath = \Yii::getAlias("@webroot") . $filePath;
                    $absFPath = \Yii::getAlias("@webroot") . $filePath . $filename;

                    if (!is_dir($absPath)) {
                        mkdir($absPath, 0777, true);
                    }

                    if (!file_exists($absFPath)) {
                        // нет файла на диске
                        $result = LexemaHelper::base64ToFile($absFPath, $fileData);
                    } else {
                        $result = true;
                    }

                    $tFile = File::find()
                            ->where(['path' => $tFilePath])
                            ->limit(1)
                            ->one();

                    if (!$tFile) {
                        // в бд нет записи
                        $tFile = new File();
                        $tFile->path = $tFilePath;
                        $tFile->title = "Акт сверки за " . $from . "-" . $to;
                        $tFile->relModel = ModelRelationHelper::REL_MODEL_CLIENT;
                        $tFile->relModelId = $client->id;
                        $tFile->type = File::TYPE_DOCUMENT;
                        $tFile->status = File::FILE_PUBLISHED;
                        $tFile->save();
                    }

                    if ($result && $tFile->save()) {
                        // если все ОК
                        return $tFilePath;
                    } else {
                        // если не ОК
                        return false;
                    }
                } else {
                    // файл не готов
                    //dump($act);
                    return false;
                }
            } else {
                continue;
                echo "Перезапрос";
                //$this->requestAct($client->id, $from, $to);
                // такого периода вообще нет в списке
                //$this->requestAct($client->id, )
                return false;
            }
        }
    }

    /** Пост запрос на получение акта сверки за период
     * @param $clientGuid
     * @param $from
     * @param $to
     * @return bool|int
     * @throws \ReflectionException
     */
    public function requestAct($clientGuid, $from, $to) {
        // Формат даты:
        // $from    05.04.2018
        // $to      14.04.2018
        // Приоритеты:
        // 1. поиск файла
        // 2. поиск в АПИ лексемы на наличие такого запроса
        // 3. в случае неудачи п.1 и п.2 отправить пост-запрос

        $client = Client::findByGuid($clientGuid);

        if (!$client) {
            return false;
        }

        // --- п.1
        $filename = $from . "-" . $to . ".pdf";
        $filePath = "/files/import/clients/$client->id/acts/" . $filename;

        $tFile = File::find()
                ->where(['path' => $filePath])
                ->limit(1)
                ->one();

        if ($tFile) {
            // запрошенный файл уже есть
            return 1;
        }

        // --- п.2
        // Перевод формата даты из:
        // 01.04.2018
        // в:
        // 2018.3.1
        $dateFrom = date_create($from)->format('Y.n.j');
        $dateFrom = LexemaHelper::dateConvert($dateFrom, false);
        $dateTo = date_create($to)->format('Y.n.j');
        $dateTo = LexemaHelper::dateConvert($dateTo, false);

        $result = ReconciliationRepository::get()
                ->findByContractorGuid($clientGuid)
                ->all();

        //$result = $this->connection->queryArray("ESI_ReconciliationAct&Contractor=$clientGuid");

        foreach ($result as $act) {
            if (($act['date1']['$value'] == $dateFrom) && ($act['date2']['$value'] == $dateTo)) {
                // данный акт уже был ранее запрошен
                return 2;
            }
        }

        // --- п.3
        // отправка пост запроса
        $post = [
            'Contractor' => $clientGuid,
            'Bdate' => $from,
            'Edate' => $to,
        ];

        $data = $this->connection->send("ESI_ReconciliationAct", $post);

        if ($data === null) {
            return 3;
        }

        return false;
    }

    /** Обновление цен по семафору
     * @param $data
     * @throws \ReflectionException
     */
    private function semaphorPrices($data) {
        if (!$this->prices) {
            $result = $this->connection->queryArray('ESI_Price');

            // Преобразование массива с прайсами из Лексемы в удобный вид
            // где key - guid продукта, value - массив со всеми его ценами
            // $_prices
            foreach ($result as $price) {
                $guid = strtoupper($price['ProductGlobalId']);
                $prices[$guid][]['EdizmGlobalId'] = $price['EdizmGlobalId'];
                $k = count($prices[$guid]) - 1;
                $prices[$guid][$k]['TypeZenaGlobalId'] = $price['TypeZenaGlobalId'];
                $prices[$guid][$k]['ozena2'] = $price['ozena2'];
            }

            $this->prices = $prices;

            unset($result);
        } else {
            $prices = $this->prices;
        }


        //dump($data);exit;

        foreach ($data as $product) {
//            dump($product);exit;
            $guid = strtoupper($product['globalId']);
//            dump($prices);exit;
            if (isset($prices[$guid])) {
                $price = $prices[$guid];
                $this->addPrice($guid, $price);
            } else {
                continue;
            }
        }
    }

    // TODO надо переделать это говно!!!!!
    /** Вторая версия семафора (через файлы)
     * @throws \ReflectionException
     * @throws \yii\base\Exception
     */
    public function semaphorChanges() {
        return;
        $fname = $this->logsPath . "semaphor.log";

        $lastUpdate = Setting::get('LEXEMA.LAST.UPDATE');
        $interval = (int) intdiv((time() - $lastUpdate), 60);
//        dump($interval);exit;
        $result = $this->connection->queryArray("ESI_GetSemaphorChanges&DepthDate=4500");
        dump($result);
        exit;
        // изменения
        $changes = $result;

        // изменения по ценам
        $prices = [];

        echo "Дата последнего обновления: " . date("Y-m-d H:i:s", $lastUpdate) . " (прошло $interval минут)" . "\n\r";

        @$file = file_get_contents($fname);

        if ($file) {
            $encode = unserialize($file);
            LexemaHelper::prepareArray($encode);
            LexemaHelper::prepareArray($changes);
            $merged = array_merge($encode, $changes);
            $merged = array_unique($merged);
            LexemaHelper::prepareArray($merged, true);
            echo "Обновлений: " . count($merged) . "\n\r";
            $data = $changes ? serialize($merged) : "";
            file_put_contents($fname, $data);
        } else {
            echo "Обновлений: " . count($changes) . "\n\r";
            $data = $changes ? serialize($changes) : "";
            file_put_contents($fname, $data);
        }

        Setting::set('LEXEMA.LAST.UPDATE', time());

        @$string = file_get_contents($fname);

        if ($string) {
            $encoded = unserialize($string);

            while ($encoded) {
                if ($encoded[0]['Model'] == 'ESI_ReconciliationAct') {
                    array_shift($encoded);
                    continue;
                }

                if ($encoded[0]['Model'] == 'ESI_Price') {
                    $prices[] = array_shift($encoded);
                    continue;
                }

                $method = $this->model[$encoded[0]['Model']];
                $result = $this->{$method}($encoded[0]['globalId']);


                if ($result) {
                    array_shift($encoded);
                } else {
                    $faulty[] = array_shift($encoded);
                }

                $data = serialize($encoded);
                $fData = "";

                file_put_contents($fname, $data);
                if (count($encoded) == 0) {
                    if (isset($faulty)) {
                        $fData = serialize($faulty);
                    }
                    file_put_contents($fname, $fData);
                }
            }
        }

        // по ценам
        if ($prices) {
            echo "Обновление цен... \n\r";
            $this->semaphorPrices($prices);
        }
        echo "Обновление завершено. \n\r";
    }

    /** Функция для запросов на новую версию получения изменений остатков
     * @param null $key1
     * @param null $key2
     * @return array|null
     */
    private function getBalanceChanges($key1 = null, $key2 = null) {
        $query = null;
        $result = null;

        if ($key1 !== null || $key2 !== null) {
            $query = "&Key1=$key1&Key2=$key2";
        }

        $changes = $this->connection->request("ESI_GetChangeStorage&Mode=1" . $query);

        $changes = json_decode($changes, true);

        if ($changes) {
            $result = [
                $changes[0]['key1'],
                +(integer) $changes[0]['key2'],
                $changes
            ];
        }

        return $result;
    }

    /**
     *  Обновление остатков на складах (по крону)
     */
    public function updateBalance() {
        $result = $this->getBalanceChanges();

        $fname = $this->logsPath . "balance.log";

        if (!$result) {
            $message = date('Y-m-d H:i:s') . " Изменений нет. " . "\r\n\r\n";
            echo $message;
            @file_put_contents($fname, $message, FILE_APPEND);
            return;
        }

        $resultTotal = [];
        $count = 0;
        do {
            $resultTotal = $resultTotal + $result[2];
            if ($count > 3) {
                $message = date('Y-m-d H:i:s') . " Превышено максимальное число итераций." . "\r\n";
                echo $message;
                $log[] = $message;
                break;
            }
            $result = $this->getBalanceChanges($result[0], $result[1]);
            $count++;
        } while ($result);

        if ($resultTotal) {
            $this->importStorageBalance($resultTotal);
        }

        $message = date('Y-m-d H:i:s') . " Обновление остатков завершено. Обновлений: " . count($resultTotal) . "\r\n";
        echo $message;
        $log[] = $message;

        $string = "";
        foreach ($log as $row) {
            $string .= $row;
        }

        @file_put_contents($fname, $string . "\r\n", FILE_APPEND);
    }

    /**
     * Импорт дисконтных карт физ. клиентов 220Вольт
     * @throws \Exception
     */
    public function importVipCard() {
        //$result = $this->connection->queryArray('ContactPerson', Connection::NAMESPACE_RETAIL);
        // телефон, номер карты, фио, сумма бонусов, сумма покупок

        $apiCards = CardRepository::find();

        foreach ($apiCards as $apiCard) {
            $dbCard = LexemaCard::find()
                    ->where(['number' => $apiCard['CardNumber']])
                    ->limit(1)
                    ->one();

            $surname = mb_strtoupper(mb_substr($apiCard['Surname'], 0, 1)) . mb_substr($apiCard['Surname'], 1);
            $name = mb_strtoupper(mb_substr($apiCard['Name'], 0, 1)) . mb_substr($apiCard['Name'], 1);
            $patronomyc = mb_strtoupper(mb_substr($apiCard['Patronymic'], 0, 1)) . mb_substr($apiCard['Patronymic'], 1);
            $fio = $surname . ' ' . $name . ' ' . $patronomyc;

            if ($dbCard) {
                //upd
                $dbCard->number = $apiCard['CardNumber'];
                $dbCard->type = $apiCard['CardType'];
                $dbCard->bonuses = $apiCard['Bonuses'];
                $dbCard->amountPurchases = $apiCard['OrdersSum'];
                $dbCard->phone = preg_replace("/[^,.0-9]/", '', $apiCard['Phone']);
                $dbCard->fio = $fio;
                $dbCard->save();
                continue;
            } else {
                $dbCard = new LexemaCard();
                $dbCard->number = $apiCard['CardNumber'];
                $dbCard->type = $apiCard['CardType'];
                $dbCard->bonuses = $apiCard['Bonuses'];
                $dbCard->amountPurchases = $apiCard['OrdersSum'];
                $dbCard->phone = preg_replace("/[^,.0-9]/", '', $apiCard['Phone']);
                $dbCard->fio = $fio;

                $dbCard->save();
                if ($dbCard->save()) {
                    echo 'Карта № ' . $apiCard['CardNumber'] . ' добавлена' . PHP_EOL;
                }
            }
        }

        echo 'Импорт карт завершен.' . PHP_EOL;
    }

    // TODO
    public function importProduction() {

        $production = ProductionRepository::get()
                ->find();

        foreach ($production as $apiProduct) {
            $dbSameVendorCodeProduct = Product::find()
                    ->where(['vendorCode' => $apiProduct['artikul']])
                    ->limit(1)
                    ->one();

            if ($dbSameVendorCodeProduct) {
                continue;
            }

            $newDbProduct = new LexemaProduction();
            $newDbProduct->loadFromRemote($apiProduct);

            dump($newDbProduct);
            exit;
            //$newDbProduct->save();
        }
    }

}
