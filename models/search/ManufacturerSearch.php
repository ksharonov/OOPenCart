<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Manufacturer;

/**
 * ManufacturerSearch represents the model behind the search form about `app\models\db\Manufacturer`.
 */
class ManufacturerSearch extends Manufacturer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'vendorCode', 'shortDescription', 'description', 'slug'], 'safe'],
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
        $query = Manufacturer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['title' => SORT_ASC]
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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'vendorCode', $this->vendorCode])
            ->andFilterWhere(['like', 'shortDescription', $this->shortDescription])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
