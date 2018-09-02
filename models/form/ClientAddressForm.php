<?php

namespace app\models\form;

use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\City;
use app\models\db\Country;
use app\models\db\UserProfile;
use app\models\db\UserToClient;
use yii\base\Model;
use app\models\db\User;
use yii\helpers\Json;

/**
 * Class ClientUserForm
 *
 * Форма добавления пользователя с клиентского меню
 *
 * @package app\models\form
 */
class ClientAddressForm extends Model
{

    private $_cityId = null;
    private $_countryId = null;

    public $id = null;

    /**
     * @var
     */
    public $country;

    /**
     * @var
     */
    public $city;

    /**
     * @var
     */
    public $postcode;

    /**
     * @var
     */
    public $address;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id'], 'string'],
            [['country', 'city', 'postcode', 'address'], 'required'],
            [['country'], 'countryValidator'],
            [['city'], 'cityValidator']
        ];
    }

    /**
     * Валидация страны
     * @param $attribute
     */
    public function countryValidator($attribute)
    {
        $country = Country::find()
            ->where(['title' => $this->$attribute])
            ->one();

        if (!$country) {
            $this->addError($attribute, 'Ошибка нахождения страны');
        } else {
            $this->_countryId = $country->id;
        }
    }

    /**
     * Валидация города
     * @param $attribute
     */
    public function cityValidator($attribute)
    {
        $city = City::find()
            ->where(['title' => $this->$attribute])
            ->one();

        if (!$city) {
            $this->addError($attribute, 'Ошибка нахождения города');
        } else {
            $this->_cityId = $city->id;
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

//        Address::deleteAll([
//            'id' => $this->id,
//            'cityId' => $this->_cityId,
//            'countryId' => $this->_countryId,
//            'address' => $this->address,
//            'postcode' => $this->postcode,
//            'relModel' => ModelRelationHelper::REL_MODEL_CLIENT,
//            'relModelId' => $user->client->id
//        ]);

        if ((bool)$this->id) {
            $addressModel = Address::findOne(['id' => $this->id]);
        } else {
            $addressModel = new Address();
        }

        $addressModel->countryId = $this->_countryId;
        $addressModel->cityId = $this->_cityId;
        $addressModel->relModel = ModelRelationHelper::REL_MODEL_CLIENT;
        $addressModel->relModelId = $user->client->id;
        $addressModel->address = $this->address;
        $addressModel->postcode = $this->postcode;
        return $addressModel->save();
    }

}