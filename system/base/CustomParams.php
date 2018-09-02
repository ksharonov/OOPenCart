<?php

namespace app\system\base;

use app\helpers\StringHelper;
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
class CustomParams extends BaseObject
{
    /** @var \stdClass Содержимое параметров */
    public $_items;

    private $_model;

    private $_isArr = false;

    /**
     * Для содержимого используется плагин MultipleInput
     * Возможно не лучшее решение завязываться на чужой пакет, но он удобный
     * @var bool
     */
    private $_isMultiple = false;

    /**
     * Если был присвоен массив
     * @var null
     */
    private $_array = null;

    public function __construct(array $config = [])
    {
        $this->_items = new \stdClass();
        parent::__construct($config);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        //Пока так для MultipleInput
        if ($this->_isMultiple) {
            foreach ($this->_items as $item) {
                if ($item['key'] == $name) {
                    return $item['value'] ?? null;
                }
            }
            return null;
        }

        if (isset($this->_items->$name)) {
            return $this->_items->$name;
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (!isset($this->_items)) {
            $this->_items = new \stdClass();
        }
//
        if ($this->_isMultiple) {
            return null;
        }

        if ($this->_items instanceof \stdClass) {
            $this->_items->$name = $value;
        }

        $this->save();
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
        } elseif (StringHelper::isJson($params)) {
            $paramsArray = Json::decode($params);
        } else {
            $paramsArray = [];
        }

        if (is_array($paramsArray) && isset($paramsArray[0]['key'])) {
            $this->_isMultiple = true;
            $this->_items = $paramsArray;
        } else {
            foreach ($paramsArray as $key => $value) {
                if (!is_int($key)) {
                    $this->setParam($key, $value);
                } else {
                    $this->_isArr = true;
                }
            }
        }

        $this->save();
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function setParam($key, $value)
    {
        if (is_null($key) || !is_string($key)) {
            return false;
        }

        if (!is_null($key) && !empty($key)) {
            $this->_items->$key = $value;
        }
    }

    /**
     * Удаление параметра
     * @param $key
     */
    public function deleteParam($key)
    {
        unset($this->_items->$key);
        $this->save();
    }

    /**
     * todo На будущее вставка простого массива
     * @param $params
     */
    public function setArray($params)
    {
        $this->_array = $params;
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
        $asArray = $this->getAsArray();
        $json = Json::encode($asArray);
        return $json;
    }

    /**
     * Выполнить перед сохранением
     */
    public function save()
    {
        if (!$this->_isArr) {
            if (!isset($this->_model->params)) {
                $this->_model->params = null;
            }

            $this->_model->params = $this->getAsJson();
        }
    }

    /**
     * Установить класс
     * @param $model
     */
    public function setModel(&$model)
    {
        $this->_model = $model;
    }

    /**
     * Геттер для MultipleInput-данных
     * @return \stdClass
     */
    public function getMultiple()
    {
        if ($this->_isMultiple && is_array($this->_items)) {
            return $this->_items;
        }
    }

}