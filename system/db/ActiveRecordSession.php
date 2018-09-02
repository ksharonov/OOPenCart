<?php

namespace app\system\db;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\Json;
use yii\base\DynamicModel;
use yii\db\ActiveRecordInterface;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use app\system\db\ActiveRecordStorage;

/**
 * Class ActiveRecordSession
 *
 * Класс для работы с данными в сессиях как будто они в БД в стиле AR
 *
 * В доработке! Не трогать!
 *
 * @package app\system\base
 */
class ActiveRecordSession extends ActiveRecordStorage
{
    public function __construct(array $config = [])
    {
        $this->storage = Yii::$app->session;
        parent::__construct($config);
    }
}