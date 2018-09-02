<?php

namespace app\modules\api\controllers;

use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\User;
use Yii;
use app\models\form\ClientAddressForm;
use app\system\base\ApiController;

class AddressController extends ApiController
{
    /**
     * Добавление адреса пользователю
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost && !Yii::$app->user->isGuest) {
            $params = \Yii::$app->request->post();

            $clientAddressForm = new ClientAddressForm();
            $clientAddressForm->load(['ClientAddressForm' => $params]);

            if (!$clientAddressForm->validate()) {
                return false;
            } else {
                $result = $clientAddressForm->save();
                return $clientAddressForm->attributes;
            }
        }

        if (Yii::$app->request->isDelete && !Yii::$app->user->isGuest) {
            $params = \Yii::$app->request->post();
            $addressId = $params['id'];

            /** @var User $user */
            $user = \Yii::$app->user->identity;
            $clientId = $user->client->id;

            return Address::deleteAll([
                'id' => $addressId,
                'relModel' => ModelRelationHelper::REL_MODEL_CLIENT,
                'relModelId' => $clientId
            ]);
        }
    }
}