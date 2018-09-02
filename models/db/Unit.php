<?php

namespace app\models\db;

use Yii;
use app\models\db\ProductUnit;

/**
 * This is the model class for table "unit".
 *
 * @property integer $id
 * @property string $title
 */
class Unit extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        ProductUnit::deleteAll([
            'unitId' => $this->id
        ]);
    }
}
