<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\db\Order`.
 */
class OrderSearch extends Order
{
    public $dt = null;
    public $category = null;
    public $orderStatus = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'clientId', 'userId', 'addressId', 'source', 'status', 'category', 'orderStatus'], 'integer'],
            [['comment', 'dt', 'dtCreate'], 'safe'],
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
        $query = self::find();
        $query->with(['userRelation', 'client', 'address']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->dt != null) {
            list($dtFrom, $dtTo) = explode('-', $this->dt);

            $dtFrom = date('Y-m-d', strtotime($dtFrom));
            $dtTo = date('Y-m-d', strtotime($dtTo));

            $query
                ->andFilterWhere(['between', 'dtCreate', $dtFrom, $dtTo]);
        }

        if ($this->category != null) {
            $query->joinWith(['content', 'content.product', 'content.product.categories']);
            $query->where(['product_category.id' => $this->category]);
        }

        //todo Фильтрация по статусу заказа пока не реализована т.к. были небольшие переделки

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'clientId' => $this->clientId,
            'userId' => $this->userId,
            'addressId' => $this->addressId,
            'source' => $this->source,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
