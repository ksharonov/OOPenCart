<?php

namespace app\system\base;

/**
 * Class Model
 *
 * Переопределённый класс модели
 *
 * @package app\system\base
 */
class Model extends \yii\base\Model
{
    public $_formName = null;

    public function __construct(array $config = [])
    {
        $this->_formName = parent::formName();
        parent::__construct($config);
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $load = parent::load($data, $formName = null);
        $this->afterLoad();
        return $load;
    }

    /**
     * Выполняется после загрузки данных
     * @return void
     */
    public function afterLoad()
    {

    }


    public function formName()
    {
        return $this->_formName;
    }

    /**
     * @param $formName
     */
    public function setFormName($formName)
    {
        $this->_formName = $formName;
    }
}