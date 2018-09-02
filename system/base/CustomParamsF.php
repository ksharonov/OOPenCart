<?php

namespace app\system\base;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class CustomParams
 *
 * Класс кастомных параметров
 *
 * Структура одной настройки
 * {
 * @property $title
 * @property $key
 * @property $value
 * }
 *
 * @package app\system\base
 *
 * @property string $asJson
 * @property array $asArray
 */
class CustomParamsF extends BaseObject
{
    /** @var \stdClass Содержимое параметров */
    public $_items;

    /**
     * @var bool
     */
    public $extraObject = true;

    public static $columns = [

    ];

    private $_model;

    public function __construct(array $config = [])
    {
        $this->_items = new \stdClass();
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->_items->$name)) {
            return $this->_items->$name;
        } else {
            return null;
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (!isset($this->_items)) {
            $this->_items = new \stdClass();
        }

        if (isset($this->_items->$name)) {
            $this->_items->$name->value = $value;
            $this->save();
            return true;
        }

//        if (!isset($this->_items->$name)) {
//            $this->_items->$name = $value;
//            $this->save();
//        }
    }

    /**
     * Установить параметры
     * @param $params
     * @return void
     */
    public function setParams($params = [])
    {
        if (is_null($params)) {
            $paramsArray = [];
        } elseif (is_array($params)) {
            $paramsArray = $params;
        } else {
            $paramsArray = Json::decode($params);
        }

        //todo пересмотреть
        if (is_null($params)) {
            return;
        }

        foreach ($paramsArray as $param) {
            $this->setParam($param);
        }
    }

    /**
     * Установить параметр
     * @param null $param
     * @return bool
     */
    public function setParam($param = null)
    {
        if (is_null($param) || !is_array($param)) {
            return false;
        }

        $cParam = new \stdClass();
        $cParam->title = $param['title'];
        $key = $cParam->key = $param['key'];
        $cParam->value = $param['value'];
        if (is_null($key) && !empty($key)) {
            $this->_items->$key = $cParam;
        }
    }

    /**
     * Вернуть как массив
     * @return array|null
     */
    public function getAsArray()
    {
        $array = ArrayHelper::toArray($this->_items);
        return count($array) > 0 ? $array : [];
    }

    /**
     * Вернуть как строку
     * @return string
     */
    public function getAsJson()
    {
        $asArray = array_values($this->getAsArray());
        $json = Json::encode($asArray);
        return $json;
    }

    /**
     * Выполнить перед сохранением
     */
    public function save()
    {
        if (!isset($this->_model->params)) {
            $this->_model->params = null;
        }

        $this->_model->params = $this->getAsJson();
    }

    /**
     * Установить класс
     * @param $model
     */
    public function setModel(&$model)
    {
        $this->_model = $model;
    }

}