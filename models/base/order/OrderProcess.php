<?php

namespace app\models\base\order;

use app\components\CartComponent;
use app\models\base\delivery\DeliveryManager;
use app\models\cookie\CartCookie;
use app\models\cookie\CityCookie;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use app\models\db\Client;
use yii\base\BaseObject;
use app\models\session\OrderSession;
use yii\web\Response;

/**
 * Class OrderProcess
 *
 * Класс процесса оформления заказа
 *
 * @package app\models\base\order
 *
 * @property string $additions
 * @property string $discounts
 */
class OrderProcess extends BaseObject
{
    /** @var OrderSession */
    private $_order;

    /** @var array */
    private $_delivery = [];

    /** @var array */
    private $_contact = [];

    /** @var array */
    private $_payment = [];

    /**
     * OrderProcess constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_order = OrderSession::get();

        $this->prepareDiscounts();

        if (isset($this->_order->model->deliveryMethod)) {
            $this->prepareDelivery();
        }

        if (isset($this->_order->model->clientType)) {
            $this->prepareContact();
            $this->checkUser();
        }

        if (isset($this->_order->model->paymentMethod)) {
            $this->preparePayment();
        }

        parent::__construct($config);
    }

    /**
     * Проверка данных
     * @return Response
     */
    public function checkUser()
    {
        if (Yii::$app->user->isGuest && $this->_order->model->clientType == Client::TYPE_ENTITY) {
            $this->_order->clientType = Client::TYPE_INDIVIDUAL;
            $this->_order->save();
            return Yii::$app->getResponse()->redirect(Url::to('/'), '302');
        }
    }

    public function prepareDiscounts()
    {
        if (Yii::$app->cart->discount && Yii::$app->client->isIndividual()) {
            $this->_order->discountData = [
                Yii::$app->cart->discount->title => Yii::$app->cart->discount->value
            ];
            $this->_order->save();
        }
    }

    /**
     * Подготовка данных по доставке
     * @return void
     */
    public function prepareDelivery()
    {
        $this->_delivery = $this->_order->deliveryData[$this->_order->deliveryMethod] ?? [
                'title' => $this->_order->delivery->extension->title
            ] ??
            null;


        /**
         * todo перенести расчёт цены доставки в каждый виджет отдельно
         */
//        dump($this->_delivery);
        /**
         * Получение доп затрат (пока платная доставка)
         */

        /** @var CityCookie $cityCookie */
        $cityCookie = CityCookie::get();
        $city = null;

        if ($cityCookie) {
            if ($cityCookie->city) {
                $city = $cityCookie->city->id ?? null;
            } else {
                $city = null;
            }
        }

        if (isset($this->_delivery['city'])) {
            $city = $this->_delivery['city'];
        }

        if (isset($this->_delivery['city']) || $cityCookie) {
            /** @var CartComponent $cartComponent */
            $cartComponent = \Yii::$app->cart;

            $deliveryManager = new DeliveryManager();
            $deliveryManager->setCity($city);
            $deliveryManager->setCost($cartComponent->sum);


            if ($deliveryManager->checkForFree()) {
                $this->_order->addCosts = [
                    'Бесплатная доставка' => '0'
                ];
            } else {
                $cost = $deliveryManager->getDeliveryCost();

                $this->_order->addCosts = [
                    'Платная доставка' => $cost
                ];
            }
        } else {
            $this->_order->addCosts = [
                'Бесплатная доставка' => '0'
            ];
        }

        //todo временно
        $isFree = $this->_order->delivery->extension->freeDelivery ?? false;
        if ($isFree) {
            $this->_order->addCosts = [
                'Бесплатная доставка' => '0'
            ];
        }
    }

    /**
     * Подготовка контактных данных
     * @return void
     */
    public function prepareContact()
    {
        $this->_contact = $this->_order->model->userData[$this->_order->model->clientType] ?? null;
    }

    /**
     * Подготовка данных по оплате
     * @return void
     */
    public function preparePayment()
    {
        $this->_order->paymentData = $this->_order->paymentData ?? [];
        $this->_payment = $this->_order->paymentData[$this->_order->paymentMethod] ?? null;
        if (isset($this->_order->lexemaCard) && (bool)$this->_order->lexemaCard['use']) {
            $this->_order->setDiscount('Бонусная карта', (float)$this->_order->lexemaCard['value'] ?? 0);
        }
    }

    /*
     * Получить выбранную информацию о доставке в виде массива
     */
    public function getDelivery()
    {
        return !$this->_delivery ? false : (object)$this->_delivery;
    }

    /**
     * Получить текстовую информацию по доставке
     * @return string
     */
    public function getDeliveryText()
    {
        if ($this->_order->delivery->extension->model && $this->_delivery) {
            return $this->_order->delivery->extension->model->getText($this->_delivery) ?? $this->_order->delivery->extension->title;
        } else {
            return "";
        }
    }

    /**
     * Выбранные данные по контактам
     */
    public function getContacts()
    {
        return !$this->_contact ? false : (object)$this->_contact;
    }

    public function getContactsText()
    {
        $data = [
            'name' => '',
            'phone' => '',
            'email' => ''
        ];

        $clientType = $this->_order->model->clientType ?? null;

        $data = array_merge($data, $this->_contact);

        if ($clientType == Client::TYPE_INDIVIDUAL) {
            return "Имя: {$data['phone']} Тел: {$data['phone']} Эл. почта: {$data['email']}";
        } elseif ($clientType == Client::TYPE_ENTITY) {
            return "";
        }
    }

    /**
     * Выбранные данные по оплате
     */
    public function getPayment()
    {
        return !$this->_payment ? false : (object)$this->_payment;
    }

    /**
     * Получить текстовую инфу по оплате
     * @return string
     */
    public function getPaymentText()
    {
        return "";
    }

    public function setContact($contact)
    {
        $this->_contact = $contact;
    }


    /**
     * Получить дополнительные затраты
     * @return array|bool
     */
    public function getAdditions()
    {
        if ($this->_order->addCosts) {
            $addCosts = $this->_order->addCosts ?? [];

            return $addCosts;
        }
        return false;
    }

    /**
     * Получить скидки
     * @return array|bool
     */
    public function getDiscounts()
    {
        if ($this->_order->discountData) {
            $discounts = $this->_order->discountData ?? [];

            return $discounts;
        }

        return false;
    }

}