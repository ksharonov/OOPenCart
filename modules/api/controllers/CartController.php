<?php

namespace app\modules\api\controllers;

use app\components\CartComponent;
use app\helpers\NumberHelper;
use app\models\db\Product;
use app\models\base\Cart;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\db\User;
use yii\helpers\ArrayHelper;

class CartController extends Controller
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
                    'set' => ['POST']
                ],
            ],
        ];
    }

    /**
     * Установка данных корзины
     *
     * @return array | bool
     */
    public function actionSet()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->post();

        if (!Yii::$app->user->isGuest) {
            if (!isset($params['cart'])) {
                return false;
            }

            /** @var User $user */
            $user = Yii::$app->user->identity;
            $profile = $user->profile;
            $profile->cartData = Json::encode($params['cart']);

            /** @var CartComponent $cartComponent */
            $cartComponent = Yii::$app->cart;
            $cart = $cartComponent->get();

            /**
             * todo  придумать структуру возвращаемого объекта,
             * чтоб данные могли записываться на идентифицируемый объект (отложено)
             */

            $retData = [
                'result' => $profile->save(),
                'data' => [
                    'binds' => [

                    ],
                    'items' => [
                        'cart' => [
                            'totalCount' => $cart->count,
                            'totalPrice' => NumberHelper::asMoney($cart->sum),
                            'totalDiscount' => NumberHelper::asMoney($cart->discount, 2),
                            'data' => [
                                'binds' => [

                                ],
                                'items' => [
                                    'cart' => [

                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            return $retData;

//            return $profile->save();
        }
        return false;
    }

    public function actionGet()
    {
        $start = microtime(true);

        $cart = \Yii::$app->cart->get();
        dump(ArrayHelper::toArray($cart));

        $finish = microtime(true);

        $delta = $finish - $start;

        echo $delta . ' сек.';
    }
}