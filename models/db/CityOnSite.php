<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "cities_on_site".
 *
 * @property integer $id
 * @property integer $cityId
 * @property City $city
 * @property string $name
 */
class CityOnSite extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city_on_site';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cityId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cityId' => 'City ID',
        ];
    }

    /**
     *  Модель города
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /** Имя города
     * @return string
     */
    public function getName()
    {
        return $this->city->title;
    }

    /** Магазины города
     * @return \yii\db\ActiveQuery
     */
    public function getShops()
    {
        return $this->hasMany(Storage::className(), ['cityId' => 'cityId']);
    }
}
