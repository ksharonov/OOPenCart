<?php

namespace app\modules\profile\controllers;

use app\models\db\Order;
use app\models\db\User;
use app\system\base\Controller;
use yii\filters\AccessControl;

class OrderController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user', 'admin']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex($id)
    {
        $orderId = (int)$id;

        if (!is_int($orderId)) {
            return $this->redirect('/profile/');
        }

        /** @var User $user */
        $user = \Yii::$app->user->identity;

        $order = Order::findOne([
            'id' => $id,
            'userId' => $user->id
        ]);

        if (!$order) {
            return $this->redirect('/profile/');
        }


        return $this->render('index', [
            'order' => $order
        ]);
    }
}