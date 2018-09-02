<?php

namespace app\system\db;

use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;

/**
 * Class ActiveRecordCookie
 *
 * Класс для работы с данными в куки как будто они в БД в стиле AR
 *
 * В доработке! Не трогать!
 *
 * @package app\system\db
 */
class ActiveRecordCookie extends ActiveRecordStorage
{
    public function __construct(array $config = [])
    {
        $this->storage = Yii::$app->cookie;
        parent::__construct($config);
    }
}