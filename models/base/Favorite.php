<?php

namespace app\models\base;

use Yii;
use app\system\base\Model;
use app\models\db\User;

/**
 * Class Favorite
 * Класс избранного
 *
 * @package app\models\base
 *
 *
 */
class Favorite extends Model
{
    /**
     * @var int id-избранного(пока нет)
     */
    public $id;

    /**
     * @var array содержимое избранного
     */
    public $items = [];

    /**
     * @var integer id-клиента
     */
    public $userId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userId'], 'integer'],
            [['items'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'items' => 'Содержимое',
            'userId' => 'Клиент'
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity ?? null;

        $this->userId = $user->id ?? null;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return User::findOne($this->userId);
    }
}