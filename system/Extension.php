<?php

namespace app\system;

use yii\base\Widget;
use app\models\db\Extension as Ext;
use yii\helpers\Json;

/**
 * Класс расширения сайта
 *
 * @property integer $id id-виджета в базе
 * @property array $_defaultParams Параметры виджета по умолчанию
 * @property array $extensionParams Параметры расширений доставки
 * @property string $_view Активная вьюха
 * @property integer $mode Режим работы
 * @static array $modes Список режимов работы виджета
 * @static array $views Список вьюх по режимам работы
 */
class Extension extends Widget
{
    const MODE_LABEL = 0;
    const MODE_SETTING = 1;
    const MODE_PROCESS = 2;
    const MODE_ADMIN = 3;

    public $id;

    public $title;

    public $params;

    public $extensionParams = [];

    public $_defaultParams = [];

    public static $modes = [
        self::MODE_LABEL => 'Выбор в заказе',
        self::MODE_SETTING => 'Режим настройки',
        self::MODE_PROCESS => 'Режим работы',
        self::MODE_ADMIN => 'Режим админа'
    ];

    public static $views = [
        self::MODE_LABEL => 'label',
        self::MODE_SETTING => 'settings',
        self::MODE_PROCESS => 'index',
        self::MODE_ADMIN => 'admin'
    ];

    public $mode = self::MODE_SETTING;

    public $_view = null;

    public $extensionModel;

    public $extensionModelClass;

    private static $instance;

    /**
     * @param $params
     * @return Extension
     */
    public static function getInstance($params = [])
    {
        if (!self::$instance) {
            self::$instance = new static($params);
        }
        return self::$instance;
    }

    public function init()
    {

        $modelWidget = Ext::findOne(['class' => static::class]);

        if (!$modelWidget->param->getAsArray()) {
            $modelWidget->params = Json::encode($this->_defaultParams);
            $modelWidget->save();
        }

        $this->id = $modelWidget->id ?? null;
        $this->params = $modelWidget->param->getAsArray();
        $this->title = $modelWidget->title;

        $this->_view = self::$views[$this->mode];

        $this->extensionParams = (object)$this->extensionParams;
//        dump($this->_defaultParams);

        parent::init();
    }

    /**
     * Получить модель расширения
     */
    public function getModel()
    {
        if (!$this->extensionModel && $this->extensionModelClass) {
            $this->extensionModel = new $this->extensionModelClass();
        }
        return $this->extensionModel;
    }
}