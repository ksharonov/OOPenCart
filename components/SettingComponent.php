<?php

namespace app\components;

use yii\base\Component;
use app\models\db\Setting;

/**
 * Class SettingComponent
 *
 * Компонент настроек сайта
 * Возможн попозже весь функционал перенсти в сам компонент
 *
 * @package app\components
 */
class SettingComponent extends Component
{

    /**
     * Получение параметра
     * @param string $key
     * @return string | int
     */
    public function get($key)
    {
        return Setting::get($key);
    }

    /**
     * Установка параметра
     * @param string $key
     * @param mixed $val
     * @return bool
     */
    public function set($key, $val)
    {
        return Setting::set($key, $val);
    }

}