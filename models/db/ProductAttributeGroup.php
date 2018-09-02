<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product_attribute_group".
 *
 * @property integer $id
 * @property string $title
 */
class ProductAttributeGroup extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название группы',
        ];
    }
}
