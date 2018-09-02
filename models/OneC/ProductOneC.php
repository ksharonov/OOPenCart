<?php

namespace app\models\OneC;

use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use app\helpers\StringHelper;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */
class ProductOneC extends OneCLoader
{
    public $source = 'product';

    public function addProductOneC()
    {

        echo 'Начало выгрузки товаров ========> ';
        $data = $this->load();
        //print_r($data);
        foreach ($data as $count_array) {
            foreach ($count_array as $product) {
                $guid_product = OuterRel::findOne([
                    'guid' => $product['ТоварGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
                ]);
                if (empty($guid_product)) {
                    $new_product = new Product();
                    $new_product->fromRemote = true;
                    $new_product->title = $product['НаименованиеТовара'];
                    $new_product->content = $product['Описание'];
                    $new_product->status = '1';
                    $new_product->slug = $new_product->id . '_' . StringHelper::translit($new_product->title);
                    //$new_product->isBest = 0;
                    //$new_product->isNew = 0;
                    $new_product->params = '[]';
                    $new_product->vendorCode = $product['АртикулПроизводителя'];
                    //$new_product->smallDescription = substr(strval($product['smallDescription']), 0, 70);
                    $new_product->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $product['ТоварGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                    $new_outer_rel->relModelId = $new_product->id;
                    $new_outer_rel->save(false);

                    $new_product_to_category = new ProductToCategory();
                    $new_product_to_category->productId = $new_product->id;
                    $new_product_to_category->categoryId = ProductCategory::findByGuid(strval($product['РодительGUID']))->id;
                    $new_product_to_category->save(false);
                } else {
                    $edit_product = Product::findOne([
                        'id' => $guid_product->relModelId,
                    ]);
                    $edit_product->title = $product['НаименованиеТовара'];
                    $edit_product->content = $product['Описание'];
                    //$edit_product->vendorCode = $product['Артикул'];
                    $edit_product->vendorCode = $product['АртикулПроизводителя'];
                    $edit_product->save(false);

                    $edit_product_to_category = ProductToCategory::findOne([
                        'productId' => $guid_product->relModelId,
                    ]);
                    $edit_product_to_category->categoryId = ProductCategory::findByGuid(strval($product['РодительGUID']))->id;
                    $edit_product_to_category->save(false);
                }

                $id_product = Product::findByGuid(strval($product['ТоварGUID']))->id;
                $guid_unit = Unit::findByGuid(strval($product['ЕдиницаИзмеренияGUID']))->id;

                if (!$guid_unit == null) {
                    ProductUnit::deleteAll([
                        'productId' => $id_product,
                        'unitId' => $guid_unit
                    ]);

                    $new_product_init = new ProductUnit();
                    $new_product_init->productId = $id_product;
                    $new_product_init->unitId = $guid_unit;
                    $new_product_init->save(false);
                }
            }
        }
        echo 'Выгрузка товаров завершена' . PHP_EOL;


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

    public function loadProduct()
    {
        $product_attribute = [];
        foreach ($this->file->ПакетПредложений->Предложения->Предложение as $item) {
            ++$this->count;
            $items[$this->count]['id'] = $item->Ид;
            $items[$this->count]['Title'] = $item->Наименование;
            $items[$this->count]['vendorCode'] = $item->Артикул;
            if ($item->ХарактеристикиТовара->ХарактеристикаТовара) {
                $count_attr = 0;
                foreach ($item->ХарактеристикиТовара->ХарактеристикаТовара as $rekvizit) {
                    ++$count_attr;
                    $product_attribute[$count_attr]['id_attr'] = $rekvizit->Ид;
                    $product_attribute[$count_attr]['name_attr'] = $rekvizit->Наименование;
                    $product_attribute[$count_attr]['value'] = $rekvizit->Значение;
                }
            }
            $items[$this->count]['product_attributes'] = $product_attribute;
            $items[$this->count]['price'] = $item->Цены->Цена->ЦенаЗаЕдиницу;
            $count_amount = 0;
            foreach ($item->Склад as $amount) {
                ++$count_amount;
                $amount_on_storage[$count_amount]['amount'] = $amount;
            }
            $items[$this->count]['amount_on_storage'] = $amount_on_storage;
        }
        return $items;
    }

    public function loadProductImportFile()
    {
        foreach ($this->file->Каталог->Товары->Товар as $item) {
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