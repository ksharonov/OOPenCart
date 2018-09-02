<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Apikey;

/**
 * ApikeySearch represents the model behind the search form about `app\models\db\Apikey`.
 */
class ApikeySearch extends Apikey
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'clientId', 'duration', 'status'], 'integer'],
            [['keyValue', 'dtCreate', 'permission'], 'safe'],
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
        $query = Apikey::find();

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
            'userId' => $this->userId,
            'clientId' => $this->clientId,
            'dtCreate' => $this->dtCreate,
            'duration' => $this->duration,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'keyValue', $this->keyValue])
            ->andFilterWhere(['like', 'permission', $this->permission]);

        return $dataProvider;
    }
}
