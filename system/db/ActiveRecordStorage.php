<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 26-03-2018
 * Time: 9:53 AM
 */

namespace app\system\db;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\Json;
use yii\base\DynamicModel;
use yii\db\ActiveRecordInterface;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use app\helpers\StringHelper;

class ActiveRecordStorage extends BaseActiveRecord implements ActiveRecordInterface
{
    /** Ключ для кастомных полей */
    const CUSTOM_KEY = 'custom';

    /**
     * Экземпляр сессий
     *
     * @var mixed
     */
    public $storage;

    /**
     * @var \stdClass
     */
    public $model;

    /**
     * Ключ модели
     *
     * @var
     */
    public $storageId;

    /**
     * Имя формы / короткое имя класса
     * @var string
     */
    public $formName;

    /**
     * Экземпляр
     * @var ActiveRecordStorage
     * @deprecated
     */
    public static $instance;

    /**
     * Экземпляры
     * @var ActiveRecordStorage[] / array
     */
    public static $instances = [];

    /**
     * @var array
     */
    private $defaultAttributes = [
        'storageId',
        'storage',
        'model',
        'formName'
    ];

    /**
     * Список атрибутов, которые могут быть сохранены в пол param
     * @var array
     */
    public $defaultParamAttributes = [

    ];

    /**
     * SessionModel constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $reflection = new \ReflectionClass($this);

        $this->formName = $reflection->getShortName();
        $this->storageId = $reflection->getShortName();

        $this->model = new DynamicModel();
        $this->setRules();
        parent::__construct($config);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        if ($this->model->__isset($name)) {
            return $this->model->$name;
        } else {
            $this->model->defineAttribute($name, null);
            return null;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if ($this->model->__isset($name)) {
            $this->model->$name = $value;
        } else {
            $this->model->defineAttribute($name, $value);
        }
        return null;
    }

    /**
     * Устанавливаем правила валидации
     * @return void
     */
    public function setRules()
    {
        $rules = $this->rules();
        foreach ($rules as $rule) {
            $rule[2] = $rule[2] ?? [];
            list($attributes, $validator, $options) = $rule;
            $this->model->addRule($attributes, $validator, $options);
        }
    }

    /**
     * Получение инстанса
     * @return mixed
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }

    /**
     * Установить инстанс
     * @param $instance
     */
    public function setInstance($instance)
    {
        /** @var string $class */
        $class = get_called_class();

        self::$instances[$class] = $instance;
    }

    /**
     * Получение модели
     * @return static
     */
    public static function get()
    {
        $instance = self::getInstance();
        if (isset($instance->storage[$instance->storageId]) && isset($instance->storage)) {
            if (!StringHelper::isJson($instance->storage[$instance->storageId])) {
                $jsonModel = [];
            } else {
                $jsonModel = Json::decode($instance->storage[$instance->storageId]);
            }

            if (!is_null($jsonModel)) {
                $dynamicModel = new DynamicModel(array_keys($jsonModel));

                foreach ($jsonModel as $key => $item) {
                    $dynamicModel->$key = $item;
                }
            } else {
                $dynamicModel = new DynamicModel();
            }

            $instance->model = $dynamicModel;
        }

        if (isset($instance->storage[self::CUSTOM_KEY . $instance->storageId])) {
            $jsonModel = Json::decode($instance->storage[self::CUSTOM_KEY . $instance->storageId]);
            foreach ($jsonModel as $key => $value) {
                $instance->setAttribute($key, $value);
            }
        }

        return $instance ?? null;
    }

    /**
     * @return ActiveRecordStorage
     */
    public static function copy()
    {
        $instance = self::get();
        $obj = new static();
        $obj->storage = $instance->storage;
        $obj->storageId = md5($instance->storageId);
        foreach ($instance->model->attributes as $attribute => $value) {
            $obj->$attribute = $value;
        }
        return $obj;
    }

    /**
     *
     * Загрузка новых данных в модель
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $formName = $formName ?? $this->formName;
        $loadData = $data[$formName];

        foreach ($loadData as $key => $value) {
            $this->$key = $value;
        }
        return true;
    }

    /**
     * Сохраняем модель
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->saveCustomAttributes();
        $this->storage->set($this->storageId, Json::encode($this->model));
        $this->storage[$this->storageId] = Json::encode($this->model);
        return true;
    }

    /**
     * Сохранение кастомных параметров
     */
    public function saveCustomAttributes()
    {
        $attributes = array_keys($this->getAttributes());
        $diffAttributes = array_diff($attributes, $this->defaultAttributes);
        $savedData = [];

        foreach ($diffAttributes as $diffAttribute) {
            $savedData[$diffAttribute] = $this->$diffAttribute;
        }
        $this->storage->set(self::CUSTOM_KEY . $this->storageId, Json::encode($savedData));
    }

    /**
     * Возвращает имя первичного ключа
     *
     * @return array
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Получает атрибут
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        return $this->$name;
    }

    /**
     * Устанавливает атрибут
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
//        $this->model->setAttributes($name, $value);
        $this->$name = $value;
//        parent::setAttribute($name, $value);
    }

    /**
     *
     * Проверка на наличие атрибута
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->model->__isset($name);
    }

    /**
     * Возвращает первичный ключ
     *
     * @param bool $asArray
     * @return string
     */
    public function getPrimaryKey($asArray = false)
    {
        return null;
    }

    /**
     * @param bool $asArray
     * @return null
     */
    public function getOldPrimaryKey($asArray = false)
    {
        return null;
    }

    /**
     * @param array $keys
     * @return bool
     */
    public static function isPrimaryKey($keys)
    {
        return false;
    }

    /**
     *
     */
    public static function find()
    {
        self::get();
    }

    /**
     * @param mixed $condition
     * @return void
     */
    public static function findOne($condition)
    {
        self::get();
    }

    /**
     * @param mixed $condition
     * @return void
     */
    public static function findAll($condition)
    {
        self::get();
    }

    /**
     * @param array $attributes
     * @param null $condition
     * @return void
     */
    public static function updateAll($attributes, $condition = null)
    {
        // TODO: Implement updateAll() method.
    }

    /**
     * @param null $condition
     * @return void
     */
    public static function deleteAll($condition = null)
    {
        $instance = self::getInstance();
        $instance->storage->remove($instance->storageId);
        $instance->storage->remove(self::CUSTOM_KEY . $instance->storageId);
    }

    /**
     * @param bool $runValidation
     * @param null $attributes
     * @return void
     */
    public function insert($runValidation = true, $attributes = null)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return void
     */
    public function update($runValidation = true, $attributeNames = null)
    {
        // TODO: Implement update() method.
    }

    /**
     *
     */
    public function delete()
    {
        $this->storage->remove($this->storageId);
        $this->storage->remove(self::CUSTOM_KEY . $this->storageId);
    }

    /**
     *
     */
    public function getIsNewRecord()
    {
        // TODO: Implement getIsNewRecord() method.
    }

    /**
     * @param ActiveRecordInterface|static $record
     * @return void
     */
    public function equals($record)
    {
        // TODO: Implement equals() method.
    }

    /**
     * @param string $name
     * @param bool $throwException
     * @return void
     */
    public function getRelation($name, $throwException = true)
    {
        // TODO: Implement getRelation() method.
    }

    /**
     * @param string $name
     * @param array|null|ActiveRecordInterface $records
     * @return void
     */
    public function populateRelation($name, $records)
    {
        // TODO: Implement populateRelation() method.
    }

    /**
     * @param string $name
     * @param ActiveRecordInterface|static $model
     * @param array $extraColumns
     */
    public function link($name, $model, $extraColumns = [])
    {
        // TODO: Implement link() method.
    }

    /**
     * @param string $name
     * @param ActiveRecordInterface|static $model
     * @param bool $delete
     */
    public function unlink($name, $model, $delete = false)
    {
        // TODO: Implement unlink() method.
    }

    /**
     *
     */
    public static function getDb()
    {
        // TODO: Implement getDb() method.
    }

    /**
     * @return array
     */
    public function getParamAttributes()
    {
        $attributes = [];

        foreach ($this->defaultParamAttributes as $attribute) {
            if (isset($this->model->$attribute)) {
                $attributes[$attribute] = $this->model->$attribute;
            }
        }

        return $attributes;
    }
}