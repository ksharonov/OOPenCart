<?php

namespace app\models\db;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\ProductPrice;

/**
 * ProductPriceSearch represents the model behind the search form about `app\models\db\ProductPrice`.
 */
class ProductPriceSearch extends ProductPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'productId', 'productPriceGroupId', 'value', 'popId'], 'integer'],
            [['dtStart', 'dtEnd'], 'safe'],
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
        $query = ProductPrice::find();

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
            'productPriceGroupId' => $this->productPriceGroupId,
            'value' => $this->value,
            'dtStart' => $this->dtStart,
            'dtEnd' => $this->dtEnd,
            'popId' => $this->popId,
        ]);

        return $dataProvider;
    }
}
