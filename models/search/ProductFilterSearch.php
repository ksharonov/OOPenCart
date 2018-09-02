<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\ProductFilter;

/**
 * ProductFilterSearch represents the model behind the search form about `app\models\db\ProductFilter`.
 */
class ProductFilterSearch extends ProductFilter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'source', 'sourceId', 'position', 'expanded'], 'integer'],
            [['title', 'params'], 'safe'],
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
        $query = ProductFilter::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'source' => $this->source,
            'sourceId' => $this->sourceId,
            'position' => $this->position,
            'expanded' => $this->expanded,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
