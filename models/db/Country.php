<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $id
 * @property string $title
 */
class Country extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id Country',
            'title' => 'Title',
        ];
    }
}
