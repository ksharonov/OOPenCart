<?php

namespace app\modules\profile\widgets\OrdersWidget;

use app\models\db\Order;
use app\models\db\User;
use Yii;
use app\models\db\Product;
use yii\base\Widget;
use app\models\search\OrderSearchProfile;
use app\models\db\Extension;

/**
 * Карточка заказа в профиле пользователя
 */
class OrdersWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $view = $this->getView();
        OrdersAsset::register($view);


        $payments = Extension::find()
            ->where(['type' => Extension::EXTENSION_TYPE_PAYMENT])
            ->all();


        $perPage = Yii::$app->request->get('per-page') ?? null;

        $searchModel = new OrderSearchProfile();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = $perPage ?? 15;

        $orderId = Yii::$app->request->get('id') ?? null;

        /** @var User $user */
        $user = Yii::$app->user->identity;

        $order = Order::find()
            ->where(['id' => $orderId])
            ->andWhere([
                'OR',
                ['userId' => $user->id],
                ['clientId' => $user->client->id]
            ])
        ->one();

        return $this->render('index', [
            'payments' => $payments,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'order' => $order
        ]);
    }
}