<?php

namespace app\modules\api\controllers;

use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\OneCCard;
use app\models\db\Setting;
use app\models\db\User;
use app\models\db\LexemaCard;
use Yii;
use app\models\form\ClientAddressForm;
use app\system\base\ApiController;
use yii\web\Response;

class BonusController extends ApiController
{
    /**
     * Тип карты по номеру
     *
     * @param string $number
     * @return array
     */
    public function actionCard($number)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $lexemaCard = LexemaCard::findOne(['number' => $number]);
        if ($lexemaCard) {
            return [
                'type' => $lexemaCard->type,
                'discount' => $lexemaCard->discountValue,
                'bonuses' => $lexemaCard->bonuses,
                'status' => true
            ];

        } else {
            return [
                'status' => false
            ];
        }
    }

    /**
     * Проверка наличия карты (без данных по карте)
     *
     * @param $number
     * @return array
     */
    public function actionCheck($number)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $lexemaCard = LexemaCard::findOne(['number' => $number]);

        if (Setting::get('ONEC.AUTH') && Yii::$app->user->identity) {
            $oneCCard = OneCCard::findOne(['number' => $number]);
            if (!$oneCCard) {
                $oneCCard = new OneCCard();
                $oneCCard->userId = Yii::$app->user->identity->id;
                $oneCCard->type = OneCCard::TYPE_DISCOUNT;
                $oneCCard->number = $number;
                $oneCCard->discountValue = $oneCCard->getDiscountValueFromOneC(); //kmplx_mega/hs/skidka?nomerkarty=1704822
                $oneCCard->save();
            }
            return [
                'status' => true
            ];
        }


        if ($lexemaCard) {
            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }
}