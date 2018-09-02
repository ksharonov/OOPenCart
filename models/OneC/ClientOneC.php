<?php

namespace app\models\OneC;
use app\models\db\Client;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class ClientOneC extends OneCLoader
{
    public $source = 'client';

    public function addClientOneC () {

        echo 'Начало выгрузки клиентов ========> ';
        $data = $this->load();
        //print_r($data);
        //exit();
        foreach ($data as $count_array) {
            foreach ($count_array as $client) {
                $guid_client = OuterRel::findOne([
                    'guid' => $client['КлиентGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_CLIENT,
                ]);
                if (empty($guid_client)){
                    $new_client = new Client();
                    $new_client->title = $client['КлиентНаименование'];
                    if ($client['ЮрФизЛицо'] == 'Компания'){
                        $new_client->type = Client::TYPE_ENTITY;
                    } else {
                        $new_client->type = Client::TYPE_INDIVIDUAL;
                    }
                    $new_client->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $client['КлиентGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_CLIENT;
                    $new_outer_rel->relModelId = $new_client->id;
                    $new_outer_rel->save(false);
                } else {
                    $upd_Client = Client::findOne([
                        'id' => $guid_client->relModelId
                    ]);
                    $upd_Client->title = $client['КлиентНаименование'];
                    $upd_Client->save(false);
                }
            }
        }
        echo 'Выгрузка клиентов завершена'.PHP_EOL;


        /*
        //$data = $this->loadProduct();
        $data = $this->load();

        $data = $this->loadProductImportFile();
        print_r($data);


        foreach ($data as $product){
            $guid_product = OuterRel::findOne([
                'guid' =>  $product['id'],
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            ]);

            if (empty($guid_product)){
                $new_product = new Product();
                $new_product->fromRemote = true;
                $new_product->title = $product['Title'];
                //$new_product->content = $product['Content'];
                $new_product->status = '1';
                $new_product->isBest = 0;
                $new_product->isNew = 0;
                $new_product->params = '[]';
                $new_product->vendorCode = $product['vendorCode'];
                //$new_product->smallDescription = substr(strval($product['smallDescription']), 0, 70);
                $new_product->save(false);

                $new_outer_rel = new OuterRel();
                $new_outer_rel->guid = $product['id'];
                $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                $new_outer_rel->relModelId = $new_product->id;
                $new_outer_rel->save(false);

                $new_product_to_category = new ProductToCategory();
                $new_product_to_category->productId = $new_product->id;
                $new_product_to_category->categoryId = ProductCategory::findByGuid(strval($product['productCategory']))->id;
                $new_product_to_category->save(false);
            }*/
            /*
            foreach ($product['amount_on_storage'] as $amount){
                $guid_storage = OuterRel::findOne([
                    'guid' =>  (string)$amount["amount"]->attributes()['ИдСклада'],
                    'relModel' => ModelRelationHelper::REL_MODEL_STORAGE
                ]);
                if ($amount["amount"]->attributes()['КоличествоНаСкладе'] > 0) {
                    $add_new_storage_balance = new StorageBalance();
                    $add_new_storage_balance->storageId = $guid_storage->relModelId;
                    $add_new_storage_balance->productId = $guid_product->relModelId;
                    $add_new_storage_balance->quantity = $amount["amount"]->attributes()['КоличествоНаСкладе'];
                    $add_new_storage_balance->save(false);

                }
            }
        }*/


    }

    public function loadProduct(){
        $product_attribute = [];
        foreach($this->file->ПакетПредложений->Предложения->Предложение as $item){
            ++$this->count;
            $items[$this->count]['id'] = $item->Ид;
            $items[$this->count]['Title'] = $item->Наименование;
            $items[$this->count]['vendorCode'] = $item->Артикул;
            if ($item->ХарактеристикиТовара->ХарактеристикаТовара) {
                $count_attr = 0;
                foreach ($item->ХарактеристикиТовара->ХарактеристикаТовара as $rekvizit){
                    ++$count_attr;
                    $product_attribute[$count_attr]['id_attr'] = $rekvizit->Ид;
                    $product_attribute[$count_attr]['name_attr'] = $rekvizit->Наименование;
                    $product_attribute[$count_attr]['value'] = $rekvizit->Значение;
                }
            }
            $items[$this->count]['product_attributes'] = $product_attribute;
            $items[$this->count]['price'] = $item->Цены->Цена->ЦенаЗаЕдиницу;
            $count_amount = 0;
            foreach ($item->Склад as $amount)
            {
                ++$count_amount;
                $amount_on_storage[$count_amount]['amount'] = $amount;
            }
            $items[$this->count]['amount_on_storage'] = $amount_on_storage;
        }
        return $items;
    }

    public function loadProductImportFile(){
        foreach($this->file->Каталог->Товары->Товар as $item){
            ++$this->count;
            $items[$this->count]['id'] = $item->Ид;
            $items[$this->count]['Title'] = $item->Наименование;
            $items[$this->count]['vendorCode'] = $item->Артикул;
            $items[$this->count]['smallDescription'] = $item->Описание;
            $items[$this->count]['productCategory'] = $item->Группы->Ид;
        }
        return $items;
    }
}