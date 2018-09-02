<?php

namespace app\modules\profile\widgets\AccessWidget;

use app\models\db\UserToClient;
use Yii;
use yii\base\Widget;
use app\models\db\User;

/**
 * Class AccessWidget
 *
 * Виджет разрешений пользователей клиента на странице пользователя
 *
 * @package app\modules\profile\widgets\AccessWidget
 */
class AccessWidget extends Widget
{
    public function run()
    {
        $view = $this->getView();
        AccessAsset::register($view);
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $client = $user->client;

        $users = UserToClient::find()
            ->joinWith('user')
            ->where(['user_to_client.clientId' => $client->id])
            ->andWhere(
                [
                    'OR',
                    ['<>', 'user.status', User::STATUS_DELETED],
                    'user.status IS NULL'
                ]
            )->all();

        return $this->render('index', [
            'user' => $user,
            'client' => $client,
            'users' => $users
        ]);
    }
}