<?php

namespace app\models\base\delivery;

use app\models\db\City;
use app\models\db\Setting;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * Class DeliveryManager
 *
 * Содержимое DELIVERY.COST.FREE должно быть:
 * (object) {cityId => {minPriceForFree => 5000, deliveryCost => 500}}
 *
 * Дополнительные расчёты по доставке
 *
 * @package app\models\base\delivery
 */
class DeliveryManager extends BaseObject
{
    public $rules = [];

    private $cost = null;
    private $cityId = null;

    /**
     * DeliveryManager constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $preRules = Json::decode(Setting::get('DELIVERY.COST.FREE'));
        $this->rules = [];

        foreach ($preRules as $rule) {
            $this->rules[$rule['cityId']] = $rule;
        }

        parent::__construct($config);
    }

    /**
     * Установить сумма заказа в корзине или в заказе
     *
     * @param $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Установить город заказа
     *
     * @param $city
     * @return $this
     * @throws Exception
     */
    public function setCity($city)
    {
        if (is_int($city)) {
            $cityId = $city;
        } else if (is_string($city)) {
            $cityModel = City::find()
                ->where(['title' => $city])
                ->one();
            $cityId = $cityModel->id ?? null;
        } else {
            $cityId = null;
            //todo тут вообще надо эксепшн бы выводить
//            throw new Exception('Неверный тип города');
        }
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Проверка на бесплатность доставки
     *
     * @return bool
     */
    public function checkForFree()
    {
        if (is_null($this->cityId)) {
            return true;
        }

        if (isset($this->rules[$this->cityId])) {
            $cityData = (object)$this->rules[$this->cityId];
        } else {
            return false;
        }

        if ($cityData) {
            return $this->cost > $cityData->minPriceForFree;
        }

        return false;
    }

    public function getDeliveryCost()
    {
        if (is_null($this->cityId)) {
            return "0";
        }
        if (isset($this->rules[$this->cityId])) {
            $cityData = (object)$this->rules[$this->cityId] ?? null;
        } else {
            return "500";//todo порешать с другими городами
        }

        if ($cityData) {
            return $cityData->deliveryCost;
        }
    }
}