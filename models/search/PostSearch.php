<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Post;

/**
 * PostSearch represents the model behind the search form about `app\models\db\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'categoryId', 'type'], 'integer'],
            [['title', 'content', 'dtCreate', 'dtUpdate', 'thumbnail', 'slug'], 'safe'],
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
        $query = Post::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->type) {
            $this->type = Post::TYPE_NEWS;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'dtCreate' => $this->dtCreate,
            'status' => $this->status,
            'dtUpdate' => $this->dtUpdate,
            'categoryId' => $this->categoryId,
            'type' => $this->type,
            'slug' => $this->slug
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail]);

        $query->orderBy('dtCreate DESC');

        return $dataProvider;
    }
}
