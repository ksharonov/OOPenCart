<?php

namespace app\models\db;

use Yii;
use kartik\editable\Editable;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property string $setKey
 * @property string $setValue
 * @property string $title
 * @property integer $type
 * @property array $params
 * @static Setting $instance
 * @static array $types
 */
class Setting extends ActiveRecord
{

    private static $instance;
    /** @var array */
    private $_settings = [];

    /** @var array */
    private $_params = [];

    const TYPE_TEXT = 0;
    const TYPE_SELECT = 1;
    const TYPE_CHECKBOX = 2;

    /**
     * @var array
     * Описание типов
     */
    public static $types = [
        self::TYPE_TEXT => 'Текст',
        self::TYPE_SELECT => 'Выбор',
        self::TYPE_CHECKBOX => 'Чекбокс'
    ];

    /**
     * @var array
     * Связь с типами редактируемых полей в Editable
     */
    public static $typesRel = [
        self::TYPE_TEXT => Editable::INPUT_TEXT,
        self::TYPE_SELECT => Editable::INPUT_SELECT2,
        self::TYPE_CHECKBOX => Editable::INPUT_CHECKBOX,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['setKey'], 'string', 'max' => 48],
            [['setValue'], 'string', 'max' => 1024],
            [['title'], 'string', 'max' => 128],
            [['params'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'setKey' => 'Ключ',
            'setValue' => 'Значение',
            'type' => 'Тип данных',
        ];
    }

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $config = self::find()->asArray()->all();
        foreach ($config as $c) {
            $this->_settings[$c["setKey"]] = $c["setValue"];

            $params = Json::decode($c["params"]) ?? [];

            foreach ($params as $param) {
                if (isset($param['key'])){
                    $this->_params[$c["setKey"]][$param['key']] = $param['value'];
                }
            }
        }
        parent::__construct();
    }

    public function beforeSave($insert)
    {
        if (!is_string($this->params)){
            $this->params = Json::encode($this->params);
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        parent::afterFind();
    }

    /**
     * Возвращает экземпляр этого класса
     *
     * @return Setting
     */
    public static function getConfig()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Получение параметра настройки по имени
     *
     * @param $key
     * @throws Exception
     * @return string | int
     */
    public static function get($key)
    {
        $config = self::getConfig();
        $params = $config->_settings;
        $result = isset($params[$key]) ? $params[$key] : null;

        if (is_null($result)) {
            throw new Exception("Отсутствует нужный параметр");
        }

        if (is_numeric($result)) {
            return (int)$result;
        } else {
            return $result;
        }

    }

    /**
     * Получение параметров параметра (масло маслянное)
     *
     * @param $key
     * @param $keyParam
     * @return array | string
     */
    public static function getParam($key, $keyParam)
    {
        $config = self::getConfig();
        $params = $config->_params;
        $result = isset($params[$key][$keyParam]) ? $params[$key][$keyParam] : null;
        return $result;
    }

    /**
     * Устанавливает параметр настройки
     *
     * @param $key
     * @param $val
     * @return boolean
     */
    public static function set($key, $val): bool
    {

        $config = self::getConfig();
        $cfg = $config->_settings;

        $params = self::findOne(['setKey' => $key]);

        $res = false;

        if ($params) {
            $params->setValue = $val;
            $res = $params->save(false);
        }

        return $res;
    }

    public function getSourceData()
    {
        return (object)["classname" => self::getParam($this->setKey, "Имя класса")] ?? null;
    }
}
