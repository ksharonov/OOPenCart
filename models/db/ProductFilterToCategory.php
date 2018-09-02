<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product_filter_to_category".
 *
 * @property integer $id
 * @property integer $filterId
 * @property integer $categoryId  //Это поле не категория фильтра, а поле привязки к категории товарам
 */
class ProductFilterToCategory extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_filter_to_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filterId', 'categoryId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filterId' => 'Filter ID',
            'categoryId' => 'Product Category ID',
        ];
    }

    /**
     * Возвращает категорию
     *
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'categoryId']);
    }
}
