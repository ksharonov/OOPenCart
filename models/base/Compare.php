<?php

namespace app\models\base;

use app\models\db\Product;
use app\system\base\Model;

/**
 * Class Compare
 *
 * Класс сравнения
 *
 * @package app\models\base
 */
class Compare extends Model
{

    /**
     * @var integer id-сравнения (пока не используется)
     */
    public $id;

    /**
     * @var array содержимое сравнения
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
            [['id'], 'integer'],
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
}