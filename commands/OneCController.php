<?php

namespace app\commands;

use app\models\db\User;
use app\models\OneC\VendorCodeOneC;
use yii\console\Controller;
use app\helpers\ImportHelper;
use app\models\db\Product;
use app\models\db\File;
use app\models\OneC\CheckStatusOrdersOneC;
use app\models\db\ProductAttribute;
use app\models\db\ProductToAttribute;
use app\models\OneC\LoadInfoOneC;
use app\helpers\ModelRelationHelper;
use app\helpers\StringHelper;
use function GuzzleHttp\Psr7\build_query;

/**
 * Class OneCController
 *
 * Консольные команды
 *
 * @package app\commands
 */
class OneCController extends Controller {

    /**
     * Импорт данных с 1С
     */
    public function actionImport() {
        $data = ImportHelper::Import1C();
        dump($data);
    }

    public function actionCheckStatus() {
        $modelCheckStatus = new CheckStatusOrdersOneC();
        $modelCheckStatus->CheckStatusOrdersOneC();
    }

    public function actionPassword($username, $newPassword) {
        $user = User::findByUsername($username);
        $user->password = $newPassword;
        $user->save();
    }

    public function actionTest() {
        $result = ImportHelper::CheckTestConnection1C();
        if ($result) {
            echo 'good connection ver 3';
        } else {
            echo 'bad connection ver 3';
        }
    }

    public function actionAddAttr() {

        //echo 'Получаем список артикулов ШЭ и Dekraft' .PHP_EOL;
        $loadVendors = new VendorCodeOneC();
        $vendorCodesArray = $loadVendors->getVendorCodeOneC();

        $nom_groups_from_onec = $loadVendors->getGuidChartOneC();

        $i = 0;
        if ($vendorCodesArray) {
            foreach ($vendorCodesArray as $m) {
                // перебор каждого товара
                dump('товар = ' . $m['ТоварGUID'] . ' (' . $m['vendorCode'] . ') ');
                $list_attr = [];
                $findProduct = Product::findByGuid(strval($m['ТоварGUID']));
                $titleCategory = $findProduct->categories[0]['title'];
                foreach ($nom_groups_from_onec as $nom_group) {
                    //dump($nom_group);
                    if ($nom_group['НомГруппа'] == $titleCategory) {
                        $guid_category = $nom_group['НомГруппаGUID']; //вот эта херня нужна
                        //dump($titleCategory);
                        echo $titleCategory . ' = ' . $guid_category . '<br>';
                    }
                }

                //формируем список аттрибутов для загрузки в 1С
                $prod_to_attr = $findProduct->attrs;
                foreach ($prod_to_attr as $attr) {
                    if ($attr->attr->attributeGroupId == 2) {
                        $list_attr[] = [
                            'title' => $attr->attr->title,
                            'attributeId' => $attr->attributeId,
                            'value' => $attr->attrValue,
                            'category' => $titleCategory,
                            'category_guid' => $guid_category,
                            'product' => $findProduct->title,
                            'product_guid' => $m['ТоварGUID']
                        ];
                    }
                }
                echo "::::::::::::::::::::::::::::::::::::::::\n";
                print_r($list_attr);
                
                //Начинаем загрузку аттрибутов в 1С
                foreach ($list_attr as $at) {
                    dump('аттрибут = ' . $at['title']);
                    $make_attr = new LoadInfoOneC();
                    $make_attr->sendInfoOneC($at);
                    //dump ($at);
                    //exit();
                }
                $i++;
                if ($i >= 300) {
                    exit();
                }
            }
        }
    }

    public function actionLoadImage() {

        /* // ПОКА НЕ УДАЛЯТЬ
          $sheider_id = [];
          $sheider = \app\models\db\ProductCategory::find()
          ->select('id')
          ->where(['like','title', 'Шнейдер Электрик'])
          ->asArray()
          ->all();

          //$this->getIdSheider($sheider[0]['id'], $sheider_id);
          ImportHelper::getIdSheider($sheider[0]['id'], $sheider_id);

          $vendorCodesArray = Product::find()
          ->select('product.id, title, vendorCode')
          ->innerJoin('product_to_category', 'product_to_category.productId = product.id')
          ->where(['product_to_category.categoryId' => '1397']);
          foreach ($sheider_id as $sh) {
          $vendorCodesArray->orWhere(['product_to_category.categoryId' => $sh]);
          }
          $vendorCodesArray->orderBy('product.id asc');
          $vendorCodesArray = $vendorCodesArray->asArray()->all(); */
        //================================================//

        /* $vendorCodesArray = Product::find()
          ->select('product.id, title, vendorCode')
          ->innerJoin('product_to_attribute', 'product_to_attribute.productId = product.id')
          ->where(['product_to_attribute.attrValue' => 'Schneider Electric'])
          ->orWhere(['product_to_attribute.attrValue' => 'DEKraft'])
          ->asArray()
          ->all(); */

        //================================================//
        //$vendorCodesArray = Product::find()->select('id, title, vendorCode')->where(['not',['vendorCode'=>null]])->asArray()->all();
        //$vendorCodesArray = Product::getVendorCodes;


        $loadImage = new VendorCodeOneC();
        $vendorCodesArray = $loadImage->getVendorCodeOneC();
        echo 'loaded' . PHP_EOL;

        $count = 0;
        if ($vendorCodesArray) {
            foreach ($vendorCodesArray as $m) {
                //print_r($m);
                /* $findProduct = Product::findOne([
                  'vendorCode' => $m['vendorCode'],
                  ]); */
                $findProduct = Product::findByGuid(strval($m['ТоварGUID']));
                //echo $findProduct->id;
                echo $count . '. Товар с ID == ' . $findProduct->id . PHP_EOL;

                $params = build_query(
                        [
                            'accessCode' => '2C5EezZ7dydX3RKjAAMLRME2_Jgg76ou',
                            //'commercialRef' => $m->vendorCode,
                            'commercialRef' => $m['vendorCode'],
                        //'commercialRef' => '30703DEK',
                        ]
                );


                $link = 'http://web.se-ecatalog.ru/api/JSON/getbasicdata?' . $params;

                echo $link;
                echo PHP_EOL;

                $json = file_get_contents($link);
                //dump($json);
                //exit();

                if ($json) {
                    $obj = \yii\helpers\Json::decode(mb_convert_encoding($json, "UTF-8", "windows-1251"));
                    //dump($obj);
                    foreach ($obj['data'] as $it) {
                        //dump($it);

                        foreach ($it['features'] as $feature) {
                            //echo $findProduct->id. '<br>';
                            //dump($feature);
                            //echo $m->title . '<br>';
                            //echo $feature['description'] . '<br>';
                            //echo $feature['value']['description_ru'];


                            $find_attr = ProductAttribute::findOne([
                                        'title' => $feature['description'],
                            ]);
                            if (empty($find_attr)) {
                                $new_attr = new ProductAttribute();
                                $new_attr->title = $feature['description'];
                                $new_attr->name = StringHelper::translit($feature['description']);
                                $new_attr->attributeGroupId = '2';
                                $new_attr->type = '0';
                                if (!empty($feature['description'])) {
                                    $new_attr->save(false);
                                };
                            }

                            $check_product_to_attr = ProductToAttribute::findOne([
                                        'productId' => $findProduct->id,
                                        'attributeId' => $find_attr->id,
                            ]);
                            if (empty($check_product_to_attr)) {
                                $new_value_attr = new ProductToAttribute();
                                $new_value_attr->productId = $findProduct->id;
                                $new_value_attr->attributeId = (empty($find_attr) ? $new_attr->id : $find_attr->id);

                                if ($feature['value']['description_ru'] == '') {
                                    $new_value_attr->attrValue = $feature['value']['id'];
                                } else {
                                    $new_value_attr->attrValue = $feature['value']['description_ru'];
                                }
                                if (!empty($new_attr->id)) {
                                    $new_value_attr->save(false);
                                }
                            } else {
                                if ($feature['value']['description_ru'] == '') {
                                    $check_product_to_attr->attrValue = $feature['value']['id'];
                                } else {
                                    $check_product_to_attr->attrValue = $feature['value']['description_ru'];
                                }
                                $check_product_to_attr->save(false);
                            }
                        }



                        if (!file_exists('./web/files/uploads/sdEl')) {
                            mkdir('./web/files/uploads/sdEl', 0777, true);
                        }
                        foreach ($it['images'] as $images) {
                            //dump($images);
                            //echo $images['url']. '<br>';
                            $filename = basename($images['url']);
                            //echo $filename. '<br>';
                            //$absPath = 'C:/OpenServer/domains/220volt2/web/files/uploads/sdEl/'.$findProduct->id;
                            //$absPath = './web/files/uploads/sdEl/'.$m['id'];
                            $absPath = './web/files/uploads/sdEl/' . $findProduct->id;
                            //$absPath = $_SERVER['DOCUMENT_ROOT'].'/files/uploads/sdEl/'.$findProduct->id;
                            if (!is_dir($absPath)) {
                                mkdir($absPath, 0777);
                            }
                            //file_put_contents($absPath.'/'.$filename, file_get_contents($images['url']));


                            $check_file = File::findOne([
                                        //'relModelId' =>  $m['id'],
                                        'relModelId' => $findProduct->id,
                                        'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
                                        'path' => '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename,
                            ]);
                            $check_file_in_dir = file_exists($absPath . '/' . $filename);

                            if (empty($check_file)) {
                                //$result = ImportHelper::grab_image($images['url'], $absPath);
                                $result = ImportHelper::grab_image($images['url'], $absPath . '/' . $filename);
                                $newFile = new File();
                                $newFile->type = File::TYPE_IMAGE;
                                $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                                $newFile->relModelId = $findProduct->id;
                                $newFile->path = '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename;
                                $newFile->status = File::FILE_PUBLISHED;
                                $newFile->save(false);
                                echo $count . '. Добавлено изображение в каталог и в базу == ' . $findProduct->id . PHP_EOL;
                            } else {
                                if ($check_file_in_dir == false) {
                                    $result = ImportHelper::grab_image($images['url'], $absPath . '/' . $filename);
                                    echo $count . '. Есть запись в БД. Добавлен изображение в каталог == ' . $findProduct->id . PHP_EOL;
                                }
                            }
                        }
                        $count++;
                        echo $count . '. checked ' . $findProduct->id . PHP_EOL;

                        foreach ($it['certificates'] as $certificat) {
                            //$filename = basename($certificat['url']).'.pdf';
                            $filename = basename($certificat['url']) . '.pdf';

                            //$absPath = './web/files/uploads/sdEl/'.$findProduct->id;
                            //$absPath = 'C:/OpenServer/domains/220volt2/web/files/uploads/sdEl/'.$findProduct->id;
                            //$absPath = __DIR__'C:/OpenServer/domains/220volt2/web/files/uploads/sdEl/'.$findProduct->id;
                            if (!is_dir($absPath)) {
                                mkdir($absPath, 0777);
                            }
                            //file_put_contents($absPath.'/'.$filename, file_get_contents($images['url']));


                            $check_file = File::findOne([
                                        'relModelId' => $findProduct->id,
                                        'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
                                        'path' => '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename,
                            ]);
                            $check_file_in_dir = file_exists($absPath . '/' . $filename);

                            if (empty($check_file)) {
                                //$result = ImportHelper::grab_image($images['url'], $absPath);
                                //$result = ImportHelper::grab_image($certificat['url'], './web/files/uploads/sdEl/' . $filename);
                                file_put_contents($absPath . '/' . $filename, file_get_contents($certificat['url']));
                                $newFile = new File();
                                $newFile->type = File::TYPE_CERTIFICATE;
                                $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                                $newFile->relModelId = $findProduct->id;
                                $newFile->path = '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename;
                                $newFile->status = File::FILE_PUBLISHED;
                                $newFile->save(false);
                                echo $count . '. Добавлен сертификат (' . $filename . ') в каталог и в базу == ' . $findProduct->id . PHP_EOL;
                            } else {
                                if ($check_file_in_dir == false) {
                                    $result = ImportHelper::grab_image($certificat['url'], $absPath . '/' . $filename);
                                    echo $count . '. Есть запись в БД. Добавлен сертификат (' . $filename . ') в каталог == ' . $findProduct->id . PHP_EOL;
                                }
                            }
                        }
                    }
                }
            }
        }
        echo 'done' . PHP_EOL;
    }

}
