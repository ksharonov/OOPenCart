<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 04.02.2018
 * Time: 12:16
 */

namespace app\models\OneC;

use app\models\db\Manufacturer;
use app\models\db\OuterRel;
use app\models\db\ProductAttribute;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;


use app\models\db\ProductToAttribute;
use app\models\db\Storage;
use app\models\db\StorageBalance;
use app\system\base\OneCLoader;

class ProductToAttributeOneC extends OneCLoader
{
    public $source = 'attr_value';

    public function addProductToAttributeOneC()
    {
        echo 'Начало выгрузки значения аттрибутов ========> ';
        $data = $this->load();
        //print_r($data);
        foreach ($data as $count_array) {
            foreach ($count_array as $amount) {
                //echo $amount['АттрибутGUID'];
                $guid_product = Product::findByGuid(strval($amount['НоменклатураGUID']))->id;
                //$guid_attr = ProductAttribute::findByGuid(strval($amount['АттрибутGUID']))->id;
                $guid_attr = null;
                $all = ProductAttributeAliasOnec::find()
                    ->all();
                foreach ($all as $one) {
                    $one->param->getAsArray();
                    $array_guids_alias = $one->getParamsArray();
                    if (in_array($amount['АттрибутGUID'], $array_guids_alias)) {
                        $guid_attr = $one->attributes['attributeId'];
                    }
                }
                $check_balance_on_storage = ProductToAttribute::findOne([
                    'productId' => $guid_product,
                    'attributeId' => $guid_attr,
                ]);

                if (empty($check_balance_on_storage)) {
                    $check_balance_on_storage = new ProductToAttribute();
                    $check_balance_on_storage->attributeId = $guid_attr;
                    $check_balance_on_storage->productId = $guid_product;
                    $check_balance_on_storage->attrValue = $amount['Значение'];
                    $check_balance_on_storage->save(false);
                } else {
                    $check_balance_on_storage->attrValue = $amount['Значение'];
                    $check_balance_on_storage->save(false);
                }

                $this->createManufacturer($check_balance_on_storage);

            }
        }
        echo 'Выгрузка значения аттрибутов  завершена' . PHP_EOL;
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

    /**
     * Добавляем производителя
     * @param ProductToAttribute $productToAttribute
     */
    public function createManufacturer(ProductToAttribute $productToAttribute)
    {
        if ($productToAttribute->attr->title == 'Торговая марка') {
            $manufacturer = Manufacturer::findOne(['title' => $productToAttribute->attrValue]);
            if (!$manufacturer) {
                $manufacturer = new Manufacturer();
                $manufacturer->title = $productToAttribute->attrValue;
                $manufacturer->save(false);
            }
            $product = Product::findOne(['id' => $productToAttribute->productId]);
            $product->manufacturerId = $manufacturer->id;
            $product->save(false);
        }
    }

}