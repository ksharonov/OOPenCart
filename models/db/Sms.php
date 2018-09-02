<?php

namespace app\models\db;

use Yii;
use yii\httpclient\Client;

/**
 * This is the model class for table "sms".
 *
 * @property integer $id
 * @property string $code
 * @property integer $success
 * @property integer $type
 * @property integer $phone
 * @property integer $attempts
 */
class Sms extends \yii\db\ActiveRecord
{
    const IS_NOT_SUCCESS = 0;
    const IS_SUCCESS = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['success', 'type', 'phone', 'attempts'], 'integer'],
            [['code'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'success' => 'Success',
            'type' => 'Type',
            'phone' => 'Phone',
            'attempts' => 'Attempts',
        ];
    }

    public static function sendCodeToUser(User $user)
    {
        return self::setCodeToPhone($user->phone);
    }

    public static function setCodeToPhone($phone)
    {
        $sms = Sms::find()
            ->where(['<', 'attempts', 3])
            ->andWhere(['like', 'phone', $phone])
            ->andWhere(['success' => self::IS_NOT_SUCCESS])
            ->one();

        if (!$sms) {
            $sms = new Sms();
            $sms->code = (string)rand(1000, 99999);
            $sms->success = self::IS_NOT_SUCCESS;
            $sms->attempts = 0;
            $sms->phone = $phone;
        }

        $sms->save();

        $client = new Client(['baseUrl' => Setting::get('SMS.SERVICE.URL')]);
        $response = $client->get([
            'login' => Setting::get('SMS.SERVICE.LOGIN'),
            'psw' => Setting::get('SMS.SERVICE.PASSWORD'),
            'phones' => self::toPhone($phone),//$user->phone,
            'mes' => $sms->code
        ])->send();
//        var_dump($response->getFullUrl());
    }

    /**
     * @param User $user
     * @return Sms|array|null|\yii\db\ActiveRecord
     */
    public static function findLastSmsByUser(User $user)
    {
        return self::findLastSmsByNumber($user->phone);
    }

    /**
     * @param $phone
     * @return Sms|array|null|\yii\db\ActiveRecord
     */
    public static function findLastSmsByNumber($phone)
    {
        return Sms::find()
                ->where(['like', 'phone', $phone])
                ->orderBy('id DESC')
                ->one() ?? null;
    }

    /**
     * @param $phone
     * @return string
     */
    public static function toPhone($phone)
    {
        if (is_string($phone) && strlen($phone) == 10) {
            return "8{$phone}";
        }
        return $phone;
    }
}
