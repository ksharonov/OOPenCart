<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $regionId
 * @property integer $cityId
 * @property string $address
 * @property integer $postcode
 * @property integer $relModel
 * @property integer $relModelId
 * @property integer $status
 * @property array $data
 * @property Country $country
 * @property Region $region
 * @property City $city
 * @static array $relModels
 */
class Address extends \app\system\db\ActiveRecord
{
    const REL_MODEL_USER = 0;
    const REL_MODEL_CLIENT = 1;

    public static $relModels = [
        self::REL_MODEL_USER => 'Пользователь',
        self::REL_MODEL_CLIENT => 'Клиент'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countryId', 'regionId', 'cityId', 'postcode', 'relModel', 'relModelId', 'status'], 'integer'],
            [['address'], 'string', 'max' => 512],
            [['relModel', 'relModelId'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Страна',
            'region' => 'Регион',
            'city' => 'Город',
            'address' => 'Адрес',
            'postcode' => 'Код города',
            'relModel' => 'Связанная модель',
            'relModelId' => 'id этой модели',
            'status' => 'Статус',
            'type' => 'Тип адреса'
        ];
    }

    /*
     * Возвращает доступную минимальную информацию по адресу
     * @return array
     */
    public function getData()
    {
        return [
            'country' => $this->country->title,
            'region' => $this->region->title,
            'city' => $this->city->title,
            'address' => $this->address,
            'postcode' => $this->postcode
        ];
    }

    /**
     * Возвращает страну
     *
     * @return ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryId']);
    }

    /**
     * Возвращает регион
     *
     * @return ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    /**
     * Возвращает город
     *
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }
}
