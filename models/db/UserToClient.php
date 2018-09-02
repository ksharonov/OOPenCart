<?php

namespace app\models\db;

use Yii;
use app\models\db\Client;
use app\models\db\User;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "user_to_client".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $clientId
 * @property integer $position
 * @property array $positions
 * @property User $user
 * @property Client $client
 * @static array $positions
 */
class UserToClient extends \app\system\db\ActiveRecord
{
    const POS_DEFAULT = 0;
    const POS_SELLER = 1;
    const POS_MANAGER = 2;
    const POS_DIRECTOR = 3;

    /** @var array уровни должностей */
    public static $positions = [
        self::POS_DEFAULT => 'Связанный с пользователем клиент',
        self::POS_SELLER => 'Продавец',
        self::POS_MANAGER => 'Менеджер',
        self::POS_DIRECTOR => 'Директор'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_to_client';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord && $this->position === null) {
            $this->position = self::POS_DIRECTOR;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'clientId'], 'required'],
            [['userId', 'clientId', 'position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'Пользователь',
            'clientId' => 'Клиент',
            'position' => 'Должность'
        ];
    }

    /**
     * Связать пользователя и клиента
     * @param User $user
     * @param Client $client
     * @param integer $position
     * @return UserToClient
     */
    public static function bind(User $user, Client $client, $position = self::POS_DEFAULT)
    {
        $userToClient = new self();
        $userToClient->clientId = $client->id;
        $userToClient->userId = $user->id;
        $userToClient->position = $position;
        $userToClient->save();

        return $userToClient;
    }

    /**
     * Возвращает клиента
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), [
            'id' => 'clientId'
        ]);
    }

    /**
     * Возвращает клиента
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), [
            'id' => 'userId'
        ]);
    }
}
