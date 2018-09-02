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
use app\models\OneC\ProductAttributeAliasOnec;
use app\models\db\ProductAttributeGroup;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;


use app\system\base\OneCLoader;

class ProductAttributeOneC extends OneCLoader
{
    public $source = 'attr';

    public function addProductAttributeOneC ()
    {
        echo 'Начало выгрузки списка аттрибутов ========> ';
        $data = $this->load();
        //print_r($data);

        foreach ($data as $count_array) {
            foreach ($count_array as $attr) {

                $check_attr_group = ProductAttributeGroup::findOne([
                    'title' => 'Основной',
                ]);
                $attr_prod = ProductAttribute::findOne([
                    'title' => $attr['Аттрибут'],
                ]);

                if (empty($attr_prod)) {
                    $new_attr = new ProductAttribute();
                    $new_attr->title = $attr['Аттрибут'];
                    $new_attr->type = 0;
                    if (empty($check_attr_group)) {
                        $new_attr_group = new ProductAttributeGroup();
                        $new_attr_group->title = 'Основной';
                        $new_attr_group->save(false);
                        $new_attr->attributeGroupId = $new_attr_group->id;
                    } else {
                        $new_attr->attributeGroupId = $check_attr_group->id;
                    }
                    $new_attr->save(false);

                    $this->checkGuidInAliasTable($attr['АттрибутGUID']);
                    $new_attr_alias = new ProductAttributeAliasOnec();
                    $new_attr_alias->attributeId = $new_attr->id;
                    $new_attr_alias->params = json_encode(array($attr['АттрибутGUID']));
                    $new_attr_alias->save(false);
                } else{
                    if (empty($attr_prod->guidOneC)){
                        $new_attr_alias = new ProductAttributeAliasOnec();
                        $new_attr_alias->attributeId = $attr_prod->id;
                        $new_attr_alias->params = json_encode(array($attr['АттрибутGUID']));
                        $new_attr_alias->save(false);
                    } else {
                        $attr_prod->guidOneC->param->getAsArray();
                        $array_guids_alias = $attr_prod->guidOneC->getParamsArray();
                        if (!in_array($attr['АттрибутGUID'], $array_guids_alias)){
                            $array_guids_alias[] = $attr['АттрибутGUID'];
                            $attr_prod->guidOneC->params = json_encode($array_guids_alias);
                            $attr_prod->guidOneC->save(false);
                        }

                    }

                }

                /*
                if (empty($attr_prod->guidOneC)){
                    echo '<br> нету --------------';
                } else {
                    echo '<br>' .$attr_prod->title ;
                    $attr_prod->guidOneC->param->getAsArray();
                    $arr_attr_guid = $attr_prod->guidOneC->getParamsArray();
                    if (in_array($attr['АттрибутGUID'], $arr_attr_guid)){
                        echo '<br>естьгавень ооооооооооооо';
                    }
                }*/


                //echo gettype($guid_attr['guid']);

                /*foreach ($guid_attr['guid'] as $guid){
                    dump($guid);
                }*/

                /*
                $guid_attr = OuterRel::findOne([
                    'guid' => $attr['АттрибутGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT_ATTRIBUTE,
                ]);

                $check_attr_group = ProductAttributeGroup::findOne([
                    'title' => 'Основной',
                ]);

                if (empty($guid_attr)) {
                    $new_attr = new ProductAttributeAliasOnec();
                    $new_attr->title = $attr['Аттрибут'];
                    $new_attr->type = 0;
                    if (empty($check_attr_group)) {
                        $new_attr_group = new ProductAttributeGroup();
                        $new_attr_group->title = 'Основной';
                        $new_attr_group->save(false);
                        $new_attr->attributeGroupId = $new_attr_group->id;
                    } else {
                        $new_attr->attributeGroupId = $check_attr_group->id;
                    }
                    $new_attr->guid = Json::encode($this->paramsArray);
                    $new_attr->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $attr['АттрибутGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_PRODUCT_ATTRIBUTE;
                    $new_outer_rel->relModelId = $new_attr->id;
                    $new_outer_rel->save(false);
                } else {
                    $edit_attr = \app\models\OneC\ProductAttributeAliasOnec::findOne([
                        'id' => $guid_attr->relModelId,
                    ]);
                    $edit_attr->title = $attr['Аттрибут'];

                    $edit_attr->save(false);
                }*/
            }
        }
        echo 'Выгрузка списка аттрибутов завершена' . PHP_EOL;
    }

    public function checkGuidInAliasTable($guid)
    {
        $all = ProductAttributeAliasOnec::find()
            ->all();
        foreach ($all as $one){
            echo '=================================';
            $one->param->getAsArray();
            $array_guids_alias = $one->getParamsArray();
            if (in_array($guid, $array_guids_alias)){
                $indx = array_search($guid, $array_guids_alias);
                unset($array_guids_alias[$indx]);
                $b = array_values($array_guids_alias);
                /*$edit_guid_alias_attr = ProductAttributeAliasOnec::findOne($one->id);
                $edit_guid_alias_attr->params = json_encode($b);
                $edit_guid_alias_attr->save(false);*/

                $one->params = json_encode($b);
                $one->save(false);
            }
        }
    }
}