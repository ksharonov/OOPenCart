<?php

namespace app\models\OneC;

use app\models\db\ProductAttribute;
use Yii;
use yii\db\ActiveQuery;
use app\models\db\ProductToAttribute;
use yii\helpers\Json;

/**
 * This is the model class for table "product_attribute_alias_onec".
 *
 * @property integer $id
 * @property integer $attributeId
 * @property json $params
 * @property array $paramsArray
 */
class ProductAttributeAliasOnec extends \app\system\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product_attribute_alias_onec';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['attributeId'], 'required'],
            [['attributeId'], 'integer'],
            [['params'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'attributeId' => 'Attribute ID',
            'params' => 'Params',
        ];
    }

    /**
     * Возвращает массив поля params
     *
     * @return array
     */
    public function getParamsArray() {
        switch (gettype($this->params)) {
            case 'string':
                return Json::decode($this->params);
                break;
            case 'array':
                return $this->params;
                break;

            default:
                return [];
        }
        //var_dump($this->params);
        //return Json::decode($this->params);
    }

    /**
     * Возвращает атрибуты этого товара
     *
     * @return ActiveQuery
     */
    public function getAttrs() {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'attributeId']);
    }

}
