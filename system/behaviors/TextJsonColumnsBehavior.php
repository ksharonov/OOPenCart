<?php

namespace app\system\behaviors;

use app\helpers\StringHelper;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * Class TextJsonColumnsBehavior
 *
 * Действия для JSON-полей перед сохранением(обновлением), после нахождения, после сохранения(обновления)
 *
 * @package app\system\behaviors
 */
class TextJsonColumnsBehavior extends \yii\behaviors\AttributeBehavior
{
    /**
     * @var string[]
     */
    public $attributes = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();


    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'changeType',
            ActiveRecord::EVENT_BEFORE_INSERT => 'changeType',
            ActiveRecord::EVENT_AFTER_INSERT => 'changeType',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'changeType',
            ActiveRecord::EVENT_AFTER_UPDATE => 'changeType'
        ];
    }

    /**
     * @param $event
     * @throws Exception
     */
    public function changeType($event)
    {
        if (!is_array($this->attributes)) {
            throw new Exception('Ошибка типа входных данных. Массив должен содержать названия атрибутов.');
        }
        if ($event->name == 'beforeInsert' || $event->name == 'beforeUpdate') {
            foreach ($this->attributes as $attribute) {
                if (is_array($event->sender->$attribute)) {
                    $event->sender->$attribute = Json::encode($event->sender->$attribute ?? []);
                } elseif (StringHelper::isJson($event->sender->$attribute)) {
                    $event->sender->$attribute = $event->sender->$attribute ?? [];
                } else {
                    $event->sender->$attribute = Json::encode([]);
                }

            }
        }

        if ($event->name == 'afterFind' || $event->name == 'afterUpdate' || $event->name == 'afterInsert') {
            foreach ($this->attributes as $attribute) {
                if (is_array($event->sender->$attribute)) {
                    $event->sender->$attribute = $event->sender->$attribute ?? [];
                } elseif (StringHelper::isJson($event->sender->$attribute)) {
                    $event->sender->$attribute = Json::decode($event->sender->$attribute) ?? [];
                } else {
                    $event->sender->$attribute = [];
                }
            }
        }
    }
}