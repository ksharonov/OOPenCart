<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 04.02.2018
 * Time: 12:16
 */

namespace app\models\OneC;
use app\models\db\OuterRel;
use app\models\db\ProductAttribute;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;


use app\models\db\Storage;
use app\models\db\StorageBalance;
use app\system\base\OneCLoader;

class StorageBalanceOneC extends OneCLoader
{
    public $source = 'storage_balance';
    public function addStorageBalanceOneC ()
    {
        echo 'Начало выгрузки количества товаров на складах ========> ';
        $data = $this->load();
        //print_r($data);
        foreach ($data as $count_array) {
            foreach ($count_array as $amount) {
                $guid_product = Product::findByGuid(strval($amount['НоменклатураGUID']))->id;
                $guid_storage = Storage::findByGuid(strval($amount['СкладGUID']))->id;
                $check_balance_on_storage = StorageBalance::findOne([
                    'productId' => $guid_product,
                    'storageId' => $guid_storage,
                ]);
                if (empty($check_balance_on_storage)){
                    $add_new_storage_balance = new StorageBalance();
                    $add_new_storage_balance->storageId = $guid_storage;
                    $add_new_storage_balance->productId = $guid_product;
                    $add_new_storage_balance->quantity = $amount['КоличествоВШтуках'];
                    $add_new_storage_balance->save(false);
                } else{
                    $check_balance_on_storage->quantity = $amount['КоличествоВШтуках'];
                    $check_balance_on_storage->save(false);
                }

            }
        }
        echo 'Выгрузка количества товаров на складе завершена'.PHP_EOL;
        /*
        $data = $this->loadAttr();
        dump($data);

        foreach ($data as $attr){
            $guid_attr = OuterRel::findOne([
                'guid' =>  $attr['id'],
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_ATTRIBUTE_ONE_C,
            ]);

            if (empty($guid_attr)){
                $new_attr = new ProductAttribute();
                $new_attr->title = $attr['Title'];
                $new_attr->save(false);

                $new_outer_rel = new OuterRel();
                $new_outer_rel->guid = $attr['id'];
                $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_ATTRIBUTE_ONE_C;
                $new_outer_rel->relModelId = $new_attr->id;
                $new_outer_rel->save(false);
            }
        }*/
    }

    public function loadAttr()
    {
        $attr = [];
        if ($this->file->Классификатор->Свойства->Свойство)
        {
            foreach ($this->file->Классификатор->Свойства->Свойство as $quality) {
                ++$this->count;
                $attr[$this->count]['id'] = $quality->Ид;
                $attr[$this->count]['Наименование'] = $quality->Наименование;
            }
        }
        return $attr;
    }

    /*public function loadAttrFromReference()
    {
        $sql = 'SELECT `vendorCode` FROM `product` where `vendorCode` is NOT NULL';
        $vendorCodesArray = Product::findBySql($sql)->all();
        dump($vendorCodesArray);

    }*/

}