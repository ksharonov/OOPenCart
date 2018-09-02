<?php

namespace app\system\base;

use app\components\ClientComponent;
use app\models\cookie\CartCookie;
use yii\base\BaseObject;
use app\models\cookie\UserCookie;
use yii\helpers\Url;

/**
 * Class App
 *
 * Базовый класс приложения для инициализации перед рендером шаблона
 *
 * @package app\system\base
 */
class App
{
    private static $instance;

    public static function run()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function __construct()
    {

    }

    public function init()
    {
        $this->initUrl();
        $this->initApp();
        $this->initUser();
    }

    /**
     * Инициализация URL
     */
    public function initUrl()
    {
        Url::remember();

        $stopList = [
            'resize',
            'assets',
            'images',
            'files'
        ];

        $hasStr = false;

        $prev = Url::previous();

        foreach ($stopList as $item) {
            if (strpos($prev, $item) !== false) {
                $hasStr = true;
            }
        }

        if ($hasStr) {
            Url::remember('/');
        }
    }

    /**
     * Инициализация приложения
     */
    public function initApp()
    {
        setlocale(LC_ALL, 'ru_RU');
    }

    /**
     * Инициализация пользователя
     */
    public function initUser()
    {
        /** @var ClientComponent $clientComponent */
        $clientComponent = \Yii::$app->client;

        UserCookie::deleteAll();

        /** @var UserCookie $user */
        $user = UserCookie::get();
        $user->isEntity = $clientComponent->isEntity();
        $user->save();

//        $cart = CartCookie::get();
//        dump($cart);
//        exit;
    }

    public function initCart()
    {
//        $cart = CartCookie::get();
//        $cart->attributes();
    }
}