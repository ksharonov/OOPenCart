<?php

namespace app\modules\api\controllers;

use app\components\ClientComponent;
use app\models\db\User;
use app\models\db\UserToClient;
use app\models\form\ClientUserForm;
use app\system\base\ApiController;

class UserController extends ApiController
{
    /**
     *
     */
    public function actionCheck()
    {

    }

    /**
     * Добавление пользователя для клиента
     */
    public function actionIndex()
    {
        /** @var ClientComponent $clientComponent */
        $clientComponent = \Yii::$app->client;

        if ($clientComponent->isIndividual()) {
            return false;
        }

        /** @var User $userComponent */
        $userComponent = \Yii::$app->user->identity;

        $userData = \Yii::$app->request->post();

        if (\Yii::$app->request->isPost) {
            $clientUserForm = new ClientUserForm();
            $clientUserForm->load(['ClientUserForm' => $userData]);
            return $clientUserForm->register();
        }

        if (\Yii::$app->request->isDelete) {
            $userId = (int)$userData['userId'];
            $userToClient = UserToClient::findOne([
                'userId' => $userId,
                'clientId' => $clientComponent->get()->id
            ]);
            if ($userId == $userComponent->id) {
                return false;
            }
            if ($userToClient) {
                $userToClient->user->status = User::STATUS_DELETED;
                $userToClient->user->save();
            }
            return true;
        }
    }
}