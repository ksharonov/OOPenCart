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
use app\models\OneC\ProductAttributeAliasOnec;
use app\models\OneC\ProductAttributeOneC;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class LoadInfoOneC extends OneCLoader
{

    public function sendInfoOneC ($at)
    {

        //1. Проверка на существование данного реквизита в 1С.
        if($this->checkExistAttrInOneC($at) == false){
            echo "реквизит не найден\n";
            return;
        }
        //exit();
        //2. Создаем реквизит-аттрибут в плане характеристик
        $types = [];
        $types['Types'] = [
            "0" => "String"
        ];
        $types['NumberQualifiers'] = [
            "AllowedSign"=> "Any",
            "Digits"=> 0,
            "FractionDigits"=> 0
        ];
        $types['StringQualifiers'] = [
            "AllowedLength"=> "Variable",
            "Length"=> 0
        ];
        $types['DateQualifiers'] = [
            "DateFractions"=> "DateTime"
        ];
        $types['BinaryDataQualifiers'] = [
            "AllowedLength"=> "Variable",
            "Length"=> 0
        ];

        $this->data = [
            "Description"=> $at['title'].' ('.$at['category'].')',
            "ValueType"=> $types,
            "Виден"=> true,
            "ЗаполнятьОбязательно"=> false,
            "ВладелецДополнительныхЗначений_Key"=> "00000000-0000-0000-0000-000000000000",
            "ДополнительныеЗначенияИспользуются"=> false,
            "ЗаголовокФормыВыбораЗначения"=> "",
            "ЗаголовокФормыЗначения"=> "",
            "Комментарий"=> "",
            "НаборСвойств_Key"=> $at['category_guid'],
            "ДополнительныеЗначенияСВесом"=> false,
            "Доступен"=> true,
            "ВыводитьВВидеГиперссылки"=> false,
            "Заголовок"=> $at['title'],
            "МногострочноеПолеВвода"=> 0
        ];


        $test = new TestConnectOneC();
        $result = $test->addTestConnectOneC();
        if ($result){
            echo 'good connection <br>';
            $this->typeOneC = 'ChartOfCharacteristicTypes';
            $this->documentOneC = 'ДополнительныеРеквизитыИСведения';
            $new_elem = $this->createObjOneC();

            $guid_new_elem = json_decode($new_elem->content)->Ref_Key;
            //(===========================)//
            $this->addProductAttributeAlias($at, $guid_new_elem);
            $this->data = [
                "Имя"=> $at['title'].'_'.$guid_new_elem,
            ];
            $this->documentOneC = "ДополнительныеРеквизитыИСведения(guid'".$guid_new_elem."')";
            $this->updateObjOneC();

            //====================================//
            //3. Созданный реквизит-аттрибут добавляем к нашей номенклатурной группы чтобы его было видно в справочнике "номенклатуры"
            $this->typeOneC = 'Catalog';
            $this->documentOneC = "НаборыДополнительныхРеквизитовИСведений(guid'".$at['category_guid']."')";
            $get_additional_details = $this->getObjOneC();
            $array_addit_details = json_decode($get_additional_details->content)->ДополнительныеРеквизиты;
            //dump($array_addit_details);
            $array_addit_details[] = [
                'LineNumber' => count(json_decode($get_additional_details->content)->ДополнительныеРеквизиты) + 1,
                'Свойство_Key' => $guid_new_elem,
                'ПометкаУдаления' => false
            ];
            //dump($array_addit_details);
            $this->data = [
                "ДополнительныеРеквизиты"=> $array_addit_details,
            ];
            $this->updateObjOneC();

            //====================================//
            //4. Обновляем номенклатуру и проставляем значение нашего нового реквизит-аттрибута
            $this->documentOneC = "Номенклатура(guid'".$at['product_guid']."')";
            $get_product = $this->getObjOneC();
            $array_attr_product = json_decode($get_product->content)->ДополнительныеРеквизиты;
            $array_attr_product[] = [
                'LineNumber' => count(json_decode($get_product->content)->ДополнительныеРеквизиты) + 1,
                'Свойство_Key' => $guid_new_elem,
                'Значение' => $at['value'],
                "Значение_Type"=> "Edm.String",
                "ТекстоваяСтрока"=> ""
            ];
            $this->data = [
                "ДополнительныеРеквизиты"=> $array_attr_product,
            ];
            $this->updateObjOneC();
            //exit();

        } else{
            echo 'bad connection';
            return false;
        }
    }

    public function addProductAttributeAlias ($at, $guid_attr)
    {
        $attr_prod = ProductAttributeAliasOnec::findOne([
            'attributeId' => $at['attributeId'],
        ]);

        if (empty($attr_prod)) {
            $new_attr_alias = new ProductAttributeAliasOnec();
            $new_attr_alias->attributeId = $at['attributeId'];
            $new_attr_alias->params = json_encode(array($guid_attr));
            $new_attr_alias->save(false);
        } else{
            $attr_prod->param->getAsArray();
            $array_guids_alias = $attr_prod->getParamsArray();
            if (!in_array($guid_attr, $array_guids_alias)){
                $array_guids_alias[] = $guid_attr;
                $attr_prod->params = json_encode($array_guids_alias);
                $attr_prod->save(false);
            }

        }
    }

    public function checkExistAttrInOneC ($at)
    {
        //echo "Проверка атрибута: \n";
        //dump($at);
        $attr_prod = ProductAttributeAliasOnec::findOne([
            'attributeId' => $at['attributeId'],
        ]);
        
        //dump($attr_prod);
        
        if (!empty($attr_prod)){
        $attr_prod->param->getAsArray();
        $array_guids_alias = $attr_prod->getParamsArray();
            foreach ($array_guids_alias as $one_attr) {
                //dump($one_attr);
                $this->typeOneC = 'ChartOfCharacteristicTypes';
                $this->documentOneC = "ДополнительныеРеквизитыИСведения(guid'".$one_attr."')";
                $get_attribute_onec = $this->getObjOneC();
                
                return $get_attribute_onec;
            }
        } else {
            return true;
        }
    }
}