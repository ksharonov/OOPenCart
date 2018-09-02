<?php

namespace app\models\db;

use Yii;
use app\models\db\Client;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "contract".
 *
 * @property integer $id
 * @property string $number
 * @property string $dtStart
 * @property string $dtEnd
 * @property integer $clientId
 * @property integer $status
 * @property string $dateEnd
 * @property string $dateStart
 */
class Contract extends \app\system\db\ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $statuses = [
        self::STATUS_NOT_ACTIVE => 'Неактивен',
        self::STATUS_ACTIVE => 'Активен'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dtStart', 'dtEnd'], 'safe'],
            [['clientId', 'status'], 'integer'],
            [['number'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'dtStart' => 'Dt Start',
            'dtEnd' => 'Dt End',
            'clientId' => 'Client ID',
            'status' => 'status'
        ];
    }

    /**
     * Возвращает клиентов пользователя
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasMany(Client::className(), [
            'id' => 'clientId'
        ]);
    }

    public function getDateStart()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtStart);

        if ($date) {
            return $date->format('d.m.Y');
        } else {
            return $this->dtStart;
        }
    }

    public function getDateEnd()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtEnd);

        if ($date) {
            return $date->format('d.m.Y');
        } else {
            return $this->dtEnd;
        }
    }
}
