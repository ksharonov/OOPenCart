<?php

namespace app\modules\order\controllers;

use app\components\CartComponent;
use app\helpers\OrderHelper;
use app\models\db\Order;
use app\models\db\OrderStatus;
use Yii;
use app\system\base\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Cookie;
use app\models\db\Setting;
use app\models\session\OrderSession;

class IndexController extends Controller
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
                        'actions' => ['index', 'submit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
        $ord = Order::findOne($id);
        //dump($ord->delivery);
        //exit;
        /** @var CartComponent $cartComponent */
        $cartComponent = Yii::$app->cart;
        $cart = $cartComponent->get();

        if ($cartComponent->isNull()) {
            return $this->redirect('/');
        }

        $params = Yii::$app->request->post();
        $order = null;

        $order = OrderSession::get();

        if (!$order) {
            $order = new OrderSession();
            $order->save();
        }

        if ($params && $order->load($params)) {
            $order->save();
        }

        return $this->render('index', [
//            'cart' => $cart,
            'order' => $order
        ]);

    }

    public function actionSubmit($id = null)
    {

    }
}