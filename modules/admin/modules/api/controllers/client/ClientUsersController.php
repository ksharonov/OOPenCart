<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15-12-2017
 * Time: 16:25 PM
 */

namespace app\modules\admin\modules\api\controllers\client;


use app\models\db\UserToClient;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ClientUsersController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'set' => ['POST'],
                    'delete' => ['POST']
                ],
            ],
        ];
    }

    /**
     * Устанавливает пользователя клиенту
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->post();

        $userToClient = new UserToClient();
        $userToClient->userId = $params['userId'];
        $userToClient->clientId = $params['clientId'];
        return $userToClient->save();
    }

    /**
     * Удаляет пользователя у клиента
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionDelete()
    {
        $params = \Yii::$app->request->post();

        return UserToClient::deleteAll([
            'userId' => $params['userId'],
            'clientId' => $params['clientId']
        ]);
    }
}