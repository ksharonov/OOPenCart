<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\ProductReview;

/**
 * ProductReviewSearch represents the model behind the search form about `app\models\db\ProductReview`.
 */
class ProductReviewSearch extends ProductReview
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'productId', 'userId', 'rating', 'status'], 'integer'],
            [['title', 'comment', 'dtCreate', 'positive', 'negative', 'dtUpdate'], 'safe'],
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
        $query = ProductReview::find();

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
            'productId' => $this->productId,
            'userId' => $this->userId,
            'rating' => $this->rating,
            'dtCreate' => $this->dtCreate,
            'dtUpdate' => $this->dtUpdate,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'positive', $this->positive])
            ->andFilterWhere(['like', 'negative', $this->negative]);

        return $dataProvider;
    }
}
