<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 03.02.2018
 * Time: 13:30
 */

namespace app\models\OneC;

use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\ProductPriceGroup;

class ProductPriceGroupOneC extends OneCLoader
{
    public $source = 'product_price_group';

    public function addProductPriceGroupOneC () {

        echo 'Начало выгрузки видов цен ========> ';
        $data = $this->load();
        foreach ($data as $count_array) {
            foreach ($count_array as $product_price_group) {
                $guid_product_price_group = OuterRel::findOne([
                    'guid' =>  $product_price_group['ВидЦеныGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP,
                ]);
                if (empty($guid_product_price_group)){
                    $new_product_price_group = new ProductPriceGroup();
                    $new_product_price_group->title = $product_price_group['НаименованиеЦены'];
                    $new_product_price_group->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $product_price_group['ВидЦеныGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP;
                    $new_outer_rel->relModelId = $new_product_price_group->id;
                    $new_outer_rel->save(false);
                } else {
                    $edit_product_price_group = ProductPriceGroup::findOne([
                        'id' => $guid_product_price_group->relModelId,
                    ]);
                    $edit_product_price_group->title = $product_price_group['НаименованиеЦены'];
                    $edit_product_price_group->save(false);
                }
            }
        }
        echo 'Выгрузка видов цен завершена'.PHP_EOL;

        /*
        $data = $this->loadProductPriceGroup();

        foreach ($data as $product_price_group){
            $guid_product_price_group = OuterRel::findOne([
                'guid' =>  $product_price_group['id'],
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP,
            ]);

            if (empty($guid_product_price_group)){
                $new_product_price_group = new ProductPriceGroup();
                $new_product_price_group->title = $product_price_group['Наименование'];
                $new_product_price_group->save(false);

                $new_outer_rel = new OuterRel();
                $new_outer_rel->guid = $product_price_group['id'];
                $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_PRICE_GROUP;
                $new_outer_rel->relModelId = $new_product_price_group->id;
                $new_outer_rel->save(false);
            }
        }*/
    }

    public function loadProductPriceGroup()
    {
        $product_price_group = [];
        foreach ($this->file->ПакетПредложений->ТипыЦен->ТипЦены as $price_group){
            ++$this->count;
            $product_price_group[$this->count]['id'] = $price_group->Ид;
            $product_price_group[$this->count]['Наименование'] = $price_group->Наименование;
            $product_price_group[$this->count]['Валюта'] = $price_group->Валюта;
        }
        return $product_price_group;
    }
}