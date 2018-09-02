<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27.03.2018
 * Time: 9:01
 */

namespace app\models\cookie;


use app\models\db\City;
use app\models\db\Storage;
use app\system\db\ActiveRecordCookie;

/**
 * Class CityCookie
 * @package app\models\cookie
 * @property int $citySelected
 */
class CityCookie extends ActiveRecordCookie
{
    /** @var City */
    private static $city;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['citySelected', 'string'],
        ];
    }

    /** Возвращает модель города, выбранного на сайте
     * @return City|null|static
     */
    public function getCity()
    {
        self::$city = City::findOne($this->citySelected);
        return self::$city ?? null;
    }

    /** Возвращает массив номеров телефонов, связанных с магазинами выбранного на сайте города
     * @return array|null
     */
    public static function getPhone()
    {
        $instance = self::getInstance();

        $phone = null;

        if (isset($instance) && isset($instance->city)) {
            $shops = Storage::find()
                ->select('storage.*')
                ->joinWith('address', false)
                ->where(['address.cityId' => $instance->city->id])
                ->andWhere(['storage.type' => 1])
                ->all();

            if (!$shops) return null;

            foreach ($shops as $shop) {
                $phone[] = $shop->phone;
                // TODO убрать, когда определятся с тем, как выводить номер на сайте
                break;
            }
        }

        return $phone;
    }

    public static function getShop()
    {
        $instance = self::getInstance();
        $mainShop = null;

        if (isset($instance) && isset($instance->city)) {
            // все магазины текущего выбранного города на сайте
            $shops = Storage::find()
                ->select('storage.*')
                ->joinWith('address', false)
                ->where(['address.cityId' => $instance->city->id])
                ->andWhere(['storage.type' => 1])
                ->all();

            if (!$shops) return null;

            foreach ($shops as $shop) {
                $mainShop = $shop;
                // TODO пока берем первый попавшийся магазин (Павел)
                //break; // убрал, чтобы показывалась Михайловка))
            }
        }

        return $mainShop;
    }
}