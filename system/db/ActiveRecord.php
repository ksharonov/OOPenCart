<?php

namespace app\system\db;

use app\helpers\ModelRelationHelper;
use app\models\db\OuterRel;
use app\system\base\CustomParams;
use yii\base\UnknownPropertyException;
use yii\helpers\Json;

/**
 * Class ActiveRecord
 *
 * Переопределённый метод ActiveRecord для базового функционала для всех
 * или большинства моделей
 *
 * @package app\system\db
 * @property integer $id
 * @property string $guid
 * @property bool $isNew
 * @property string/null $dtc
 * @property string/null $dtu
 * @property string $params
 * @property string $dtCreate
 * @property string $dtUpdate
 * @property integer $relModel
 * @property CustomParams $param
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /** @var bool текстовый уникальный идентификатор */
    private $_guid = false;

    public $_param = null;

    /** @var boolean */
    public $isNew;

//    /**
//     * Отдаёт relModel для AR-записи
//     * @var null
//     */
//    public $relModelAR = null;


    public function __construct(array $config = [])
    {
//        $className = self::className();
//        $reflection = new \ReflectionClass($className);
//        $modelName = $reflection->getShortName();
//        $this->relModelAR = ModelRelationHelper::$model[$modelName] ?? null;

        $this->isNew = $this->isNewRecord;

        if (is_null($this->_param)) {
            $this->_param = new CustomParams();
            $this->_param->setModel($this);
        }

        parent::__construct($config);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
//        if (isset($this->param->_items) && isset($this->param->_items->$name)) {
//            return $this->param->$name;
//        }
        if (isset($this->param->$name) && !$this->hasAttribute($name)) {
            return $this->param->$name;
        }
        return parent::__get($name);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->hasAttribute('params') && $this->param instanceof CustomParams) {
            $this->param->setParams($this->params);
            $this->param->save();

        }

        return parent::beforeSave($insert);
    }

    /** Получаем модель по guid
     * @param string $guid
     * @param bool $onlyCheck
     * @return ActiveRecord|bool|null
     * @throws \ReflectionException
     */
    public static function findByGuid(string $guid, bool $onlyCheck = false)
    {
        $relModel = self::getRelModel();

        $method = ModelRelationHelper::getMethodName(self::className());

        $outerRelModel = OuterRel::find()
            ->where(['relModel' => $relModel])
            ->andWhere(['guid' => $guid])
            ->one();

        if (!$outerRelModel) {
            if ($onlyCheck == true) {
                return false;
            } else {
                $checkRelModel = OuterRel::findOne(['guid' => $guid]);
                if (!$checkRelModel) {
                    /** @var ActiveRecord $model */
                    $model = new static;
                    $model->guid = $guid;
                    return $model;
                }
            }
        }

        /** @var ActiveRecord $model */
        $model = $outerRelModel->$method;
        if ($onlyCheck == false) $model->guid = $guid;

        return $model;
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        $isNewRecord = array_key_exists('id', $changedAttributes) && !$changedAttributes['id'];
        $this->isNew = $isNewRecord;

        if ($this->guid && $this->isNew) {
            $this->linkGuid();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Связываем с таблицей Guid-ов
     */
    public function linkGuid()
    {
        $relModel = $this->relModel;
        if ($relModel !== null) {
            $outerRelModel = OuterRel::findOne([
                'relModel' => $relModel,
                'relModelId' => $this->id
            ]);

            if (!$outerRelModel) {
                $outerRelModel = new OuterRel();
                $outerRelModel->relModel = $relModel;
                $outerRelModel->relModelId = $this->id;
                $outerRelModel->guid = $this->guid;
                $outerRelModel->save();
            }
        }
    }

    /**
     * @return null
     */
    public static function getRelModel()
    {
        $className = static::class;
        $reflection = new \ReflectionClass($className);
        $modelName = $reflection->getShortName();
        $relModel = ModelRelationHelper::$model[$modelName] ?? null;

        return $relModel;
    }

    /**
     * Дата создания записи
     *
     * @return string
     */
    public function getDtc()
    {
        if (isset($this->dtCreate)) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtCreate);

            if ($date) {
                return $date->format('d.m.Y');
            } else {
                return $this->dtCreate;
            }
        }

        return null;
    }

    /**
     * Дата обновления записи
     *
     * @return string
     */
    public function getDtu()
    {
        if (isset($this->dtUpdate)) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtUpdate);

            if ($date) {
                return $date->format('d.m.Y');
            } else {
                return $this->dtUpdate;
            }
        }

        return null;
    }

    /**
     *
     * Получение кастомного параметра (работает для всех таблиц, где есть поле params
     * todo позже добавить везде, где можно
     *
     * @return CustomParams|null
     */
    public function getParam()
    {

        if (isset($this->params) && $this->hasAttribute('params') && $this->hasProperty('params')) {
//            @dump($this->params);
//            return null;
            // TODO какая-то дичь (ошибка с нахождение аттрибута $param у Product)
            @$this->_param->setParams($this->params);
        }
        return $this->_param;
    }

    /**
     * Получить guid модели
     *
     * @return null|string
     */
    public function getGuid()
    {
        $relModel = $this->relModel;

        if (is_null($relModel)) {
            return null;
        }

        $outerRelModel = OuterRel::findOne([
            'relModel' => $relModel,
            'relModelId' => $this->id
        ]);

        if ($outerRelModel) {
            $guid = $outerRelModel->guid;
        } elseif ($this->_guid) {
            $guid = $this->_guid;
        } else {
            $guid = null;
        }

        return $guid;
    }

    /**
     * Установить guid
     * @param $guid
     */
    public function setGuid($guid)
    {
        $this->_guid = $guid;
    }

    /**
     * Установка кастомных атрибутов
     * @param $paramAttributes
     */
    public function setParamAttributes($paramAttributes)
    {
        foreach ($paramAttributes as $key => $value) {
            $this->param->setParam($key, $value);
        }
    }
}