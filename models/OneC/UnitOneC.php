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

class UnitOneC extends OneCLoader
{
    public $source = 'unit';

    public function addUnitOneC () {

        echo 'Начало выгрузки единиц измерения ========> ';
        $data = $this->load();
        //print_r($data);
        foreach ($data as $count_array) {
            foreach ($count_array as $unit) {
                $guid_unit = OuterRel::findOne([
                    'guid' => $unit['ЕдиницаИзмеренияGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_UNIT,
                ]);
                if (empty($guid_unit)){
                    $new_unit = new Unit();
                    $new_unit->title = $unit['ЕдиницаИзмерения'];
                    $new_unit->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $unit['ЕдиницаИзмеренияGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_UNIT;
                    $new_outer_rel->relModelId = $new_unit->id;
                    $new_outer_rel->save(false);
                } else {
                    $edit_unit = Unit::findOne([
                        'id' => $guid_unit->relModelId,
                    ]);
                    $edit_unit->title = $unit['ЕдиницаИзмерения'];
                    $edit_unit->save(false);
                }
                /*
                $guid_product = Product::findByGuid(strval($unit['ТоварGUID']))->id;
                $guid_unit = Unit::findByGuid(strval($unit['ЕдиницаИзмеренияGUID']))->id;
                $new_product_init = new ProductUnit();
                $new_product_init->productId = $guid_product;
                $new_product_init->unitId = $guid_unit;
                //$new_product_init->save(false);*/
            }
        }
        echo 'Выгрузка единиц измерения завершена'.PHP_EOL;




    }
}