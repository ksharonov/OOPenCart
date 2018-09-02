<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "apikey".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $clientId
 * @property string $keyValue
 * @property string $dtCreate
 * @property integer $duration
 * @property integer $status
 * @property string $permission
 */
class Apikey extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apikey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'clientId', 'duration', 'status'], 'integer'],
            [['dtCreate'], 'safe'],
            [['keyValue'], 'string', 'max' => 50],
            [['permission'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'clientId' => 'Client ID',
            'keyValue' => 'Key Value',
            'dtCreate' => 'Dt Create',
            'duration' => 'Duration',
            'status' => 'Status',
            'permission' => 'Permission',
        ];
    }
}
