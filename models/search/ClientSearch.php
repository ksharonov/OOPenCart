<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Client;

/**
 * ClientSearch represents the model behind the search form of `app\models\db\Client`.
 */
class ClientSearch extends Client
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status'], 'integer'],
            [['title', 'dtUpdate', 'dtCreate', 'phone', 'params', 'email'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Client::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->type = $this->type ?? Client::TYPE_ENTITY;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'dtUpdate' => $this->dtUpdate,
            'dtCreate' => $this->dtCreate,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
