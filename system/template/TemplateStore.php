<?php

namespace app\system\template;

use app\models\db\Block;
use yii\base\BaseObject;

/**
 * Class TemplateStore
 *
 * Шаблонный "магазин"-данных
 *
 * @package app\system\template
 */
class TemplateStore extends BaseObject
{
    /** @var TemplateStore */
    private static $instance;

    /** @var array массив переменных */
    private $vars = [];


    /**
     * TemplateStore constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Возвращает экземпляр этого класса
     *
     * @return TemplateStore
     */
    public static function getInstance(): TemplateStore
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string / array $var
     * @param $value
     */
    public static function setVar($var, $value = null)
    {
        $instance = self::getInstance();

        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $instance->vars["{%$key%}"] = $val;
            }
        } else {
            $instance->vars["{%$var%}"] = $value;
        }
    }

    /**
     * @param string $var
     * @return mixed
     */
    public static function getVar(string $var)
    {
        $instance = self::getInstance();
        return $instance->vars["{%$var%}"];
    }

    /**
     * Замена всех переменных в шаблоне
     *
     * @param string $content
     * @return mixed
     */
    public static function process(string $content)
    {
        $instance = self::getInstance();
        $search = array_keys($instance->vars);
        $replace = array_values($instance->vars);

        return str_replace($search, $replace, $content);
    }

}