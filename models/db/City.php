<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $regionId
 * @property integer $countryId
 * @property string $title
 * @property Region $region
 * @property Country $country
 */
class City extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regionId', 'countryId'], 'required'],
            [['regionId', 'countryId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id City',
            'regionId' => 'Id Region',
            'countryId' => 'Id Country',
            'title' => 'Name',
        ];
    }

    public static function findByName($name)
    {
        return self::find()
            ->where([
                'title' => $name
            ])
            ->one();
    }

    /** Регион
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    /** Страна
     * @return \yii\db\ActiveQuery
     */
    public function getCounty()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryId']);
    }

    /**
     *  Модель города
     */
    public function getCityOnSite()
    {
        return $this->hasOne(CityOnSite::className(), ['cityId' => 'id']);
    }
}
