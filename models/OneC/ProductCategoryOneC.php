<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 13.03.2018
 * Time: 9:44
 */

namespace app\models\OneC;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;
use app\system\db\ActiveRecord;


class ProductCategoryOneC extends OneCLoader
{
    public $product_category = [];
    public $source = 'product_category';

    public function addProductCategoryOneC()
    {
        echo 'Начало выгрузки категории товаров ========> ';
        $data = $this->load();
        foreach ($data as $count_array){
            foreach ($count_array as $productCategory){
                $guid_productCategory = OuterRel::findOne([
                    'guid' =>  $productCategory['КатегорияGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY,
                ]);
                if (empty($guid_productCategory)){
                    $new_productCategory = new ProductCategory();
                    $new_productCategory->title = $productCategory['НаименованиеКатегории'];
                    $new_productCategory->parentId = ProductCategory::findByGuid(strval($productCategory['РодительGUID']))->id;
                    //$new_productCategory->parentId = ProductCategory::findByGuid($productCategory['parent'])->id == null ? Null : ProductCategory::findByGuid($productCategory['parent'])->id;
                    $new_productCategory->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $productCategory['КатегорияGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY;
                    $new_outer_rel->relModelId = $new_productCategory->id;
                    $new_outer_rel->save(false);
                } else {
                    $edit_productCategory = ProductCategory::findOne([
                        'id' => $guid_productCategory->relModelId,
                    ]);
                    $edit_productCategory->title = $productCategory['НаименованиеКатегории'];
                    $edit_productCategory->parentId = ProductCategory::findByGuid(strval($productCategory['РодительGUID']))->id;
                    $edit_productCategory->save(false);
                }
            }
        }
        echo 'Выгрузка категории товаров завершена' .PHP_EOL;


        /*
        $data = $this->loadProductCategory();
        //print_r($data);
        foreach ($data as $productCategory){
            $guid_productCategory = OuterRel::findOne([
                'guid' =>  $productCategory['id'],
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY,
            ]);
            if (empty($guid_productCategory)){
                $new_productCategory = new ProductCategory();
                $new_productCategory->title = $productCategory['title'];
                $new_productCategory->parentId = ProductCategory::findByGuid(strval($productCategory['parent']))->id;
                //$new_productCategory->parentId = ProductCategory::findByGuid($productCategory['parent'])->id == null ? Null : ProductCategory::findByGuid($productCategory['parent'])->id;
                $new_productCategory->save(false);

                $new_outer_rel = new OuterRel();
                $new_outer_rel->guid = $productCategory['id'];
                $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY;
                $new_outer_rel->relModelId = $new_productCategory->id;
                $new_outer_rel->save(false);
            }

        }*/


    }

    public function loadProductCategory()
    {
        $this->addNewLevelArray($this->product_category, $this->file->Классификатор->Группы->Группа);
        return $this->product_category;
    }

    public function addNewLevelArray(&$product_category, $level_group, $parent_id = NULL)
    {
        foreach ($level_group as $group) {
            $arr = [
                'id' => strval($group->Ид),
                'title' => strval($group->Наименование),
                'parent' => strval($parent_id),
            ];
            //print_r($group);
            //echo 'ID = ' . $group->Ид . '<br>';
            //echo 'Название = ' . $group->Наименование . '<br>';
            //echo 'Родительский = ' . $parent_id . '<br>';
            $product_category[] = $arr;
            if (isset($group->Группы->Группа)) {
                $this->addNewLevelArray($product_category, $group->Группы->Группа, $group->Ид);
            }
        }
        //print_r($product_category);
        //return $product_category;
    }
}