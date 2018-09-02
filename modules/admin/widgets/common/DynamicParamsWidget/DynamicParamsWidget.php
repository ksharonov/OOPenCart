<?php

namespace app\modules\admin\widgets\common\DynamicParamsWidget;

use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class DynamicParamsWidget
 * Виджет для кастомных значений в поле params у модели
 *
 * @package app\modules\admin\widgets\common\DynamicParamsWidget
 * @deprecated
 */
class DynamicParamsWidget extends Widget
{
    const MODE_DEFAULT = 0;
    const MODE_HARD = 1;

    /**
     * @var array массив режимов работы
     */
    public static $modes = [
        self::MODE_DEFAULT => 'Обычный объект',
        self::MODE_HARD => 'Жёсткий объект'
    ];

    /**
     * @var array массив вьюх
     */
    public static $views = [
        self::MODE_DEFAULT => 'index',
        self::MODE_HARD => 'hard'
    ];

    /**
     * @var int режим работы виджета по умолчанию
     */
    public $mode = self::MODE_DEFAULT;

    /**
     * @var string
     */
    public $label = 'Кастомные поля';

    /**
     * @var array
     * Массив полей по умолчанию
     */
    public $defaultData = null;

    /**
     * @var bool
     * Возможность добавлять новые кастомные поля
     */
    public $extendable = true;

    /**
     * @var null
     * Экземпляр класса ActiveRecord
     */
    public $model = null;

    /**
     * @var string
     * Атрибут модели
     */
    public $attribute = 'params';

    /**
     * @var array
     * Настройки виджета
     */
    public $options = [];

    /**
     * @var string выбранная вьюха
     */
    public $_view = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_view = self::$views[$this->mode];
    }

    /**
     * @return string
     */
    public function run()
    {
        $view = $this->view;
        DynamicParamsAsset::register($view);

        if (is_array($this->model->{$this->attribute})) {
            $this->model->{$this->attribute} = Json::encode($this->model->{$this->attribute});
        }

        $data = Json::decode($this->model->{$this->attribute}) ?? $this->defaultData ?? [];

        if (!is_array($data)){
            $data = [];
        }

        return $this->render('index', [
            'extendable' => $this->extendable,
            'label' => $this->label,
            'data' => $data,
            'model' => $this->model,
            'attribute' => $this->attribute
        ]);
    }

}