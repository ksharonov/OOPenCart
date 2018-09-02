<?php

namespace app\models\search;

use app\models\db\OrderStatusHistory;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Order;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\Json;
use app\models\db\User;

/**
 * OrderSearchProfile модель поиска заказов на клиентской части
 */
class OrderSearchProfile extends Order
{
    /**
     * @var null поисковая строка
     */
    public $search = null;

    /** @var array массив статусов */
    public $status = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'clientId', 'userId', 'addressId', 'source'], 'integer'],
            [['comment', 'products', 'dtCreate', 'dtUpdate', 'status', 'search'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $clientId = $user->client->id ?? null;

        if (!$user->client) {
            throw new Exception('Пользователь не привязан к клиенту!');
        }

        /** @var array $orderIds список id-ордеров, подходящих в условия */
        $orderIds = null;

        if ($this->status && $this->status[0]) {
            $orderStatusHistory = OrderStatusHistory::find()
                ->groupBy('orderId')
                ->orderBy('orderId DESC')
                ->createCommand()
                ->sql;

            $orderIds = (new \yii\db\Query())
                ->select('orderId')
                ->from("(" . $orderStatusHistory . ") tab")
                ->where(['orderStatusId' => $this->status])
                ->column();

        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->where(
            [
                'OR',
                ['order.userId' => $user->id],
                ['order.clientId' => $clientId]
            ]

        );

        if (is_array($orderIds) && count($orderIds) == 0) {
            $query->andFilterWhere([
                'id' => 0
            ]);
        } elseif ($orderIds) {
            $query->andFilterWhere([
                'id' => $orderIds
            ]);
        }

        if ($this->search) {
            $query
                ->joinWith('content')
                ->joinWith('lexemaOrder')
                ->andFilterWhere(['like', 'order_content.productData', $this->search])
                ->orFilterWhere(['like', 'lexema_order.orderNumber', $this->search]);

        }

        // grid filtering conditions
        $query->andFilterWhere([
            'addressId' => $this->addressId,
            'source' => $this->source,
            'order.dtCreate' => $this->dtCreate,
            'order.dtUpdate' => $this->dtUpdate
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);
        $query->orderBy('order.id DESC');

        return $dataProvider;
    }
}
