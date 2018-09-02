<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property integer $countryId
 * @property string $title
 */
class Region extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countryId'], 'required'],
            [['countryId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id Region',
            'countryId' => 'Id Country',
            'title' => 'Name',
        ];
    }
}
