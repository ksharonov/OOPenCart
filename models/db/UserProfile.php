<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $compareData
 * @property string $favoriteData
 * @property string $cartData
 */
class UserProfile extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'citySelected'], 'integer'],
            [['compareData', 'favoriteData', 'cartData'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (isset(Yii::$app->request->cookies)) {
            $cookies = Yii::$app->request->cookies;

            $this->compareData = $cookies->getValue('compare');
            $this->favoriteData = $cookies->getValue('favorite');
            $this->cartData = $cookies->getValue('cart');
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'compareData' => 'Compare Data',
            'favoriteData' => 'Favorite Data',
            'cartData' => 'Cart Data',
        ];
    }
}
