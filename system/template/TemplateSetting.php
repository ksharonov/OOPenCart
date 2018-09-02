<?php

namespace app\system\template;

use app\models\db\Setting;
use app\models\db\Template;
use yii\helpers\Json;
use yii\base\Exception;

/**
 * Class TemplateSetting
 *
 * Класс получения настроек шаблона
 *
 * @package app\system\template
 */
class TemplateSetting
{
    /** @var TemplateSetting */
    private static $instance;

    /** @var array */
    private $_settings = [];

    /**
     * Инициализация параметров шаблона
     *
     * @throws Exception
     * @return void
     */
    public function init()
    {
        $instance = self::getInstance();

        if (!isset($instance::$templateInstance)) {
            $selectedTemplate = Setting::get('TEMPLATE.SELECTED');

            /** @var Template $templateModel */
            $templateModel = Template::findOne($selectedTemplate);

            if (!$templateModel) {
                throw new Exception("В базе отсутствует шаблон");
            }

            $templateParams = $templateModel->params ?? [];

            $settingsArr = $templateParams['setting'] ?? [];

            foreach ($settingsArr as $item) {
                $key = $item['key'] ?? null;
                $value = $item['value'] ?? null;

                $this->_settings[$key] = $value;
            }
        }
    }

    /**
     * Возвращает экземпляр этого класса
     *
     * @return TemplateSetting
     */
    public static function getInstance(): TemplateSetting
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Получение параметра настройки по имени
     *
     * @param $key
     * @return string
     */
    public static function get($key)
    {
        $instance = self::getInstance();
        return $instance->_settings[$key] ?? null;
    }
}