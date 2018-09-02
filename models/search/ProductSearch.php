<?php

namespace app\models\search;

use app\models\db\ProductCategory;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\db\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\db\Product`.
 */
class ProductSearch extends Product
{
    public $category = null;
    public $orderStatus = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'category'], 'integer'],
            [['id', 'title', 'content', 'dtUpdate', 'dtCreate', 'backCode', 'vendorCode'], 'safe'],
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
//        dump($params);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->status == null) {
            $this->status = Product::STATUS_PUBLISHED;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->category = $this->category == '' ? null : $this->category;

        if ($this->backCode != null) {
            $query->filterWhere([
                'backCode' => $this->title
            ]);
            $titleArray = [];
        } else {
            $titleArray = explode(' ', $this->title);
        }


        $queryParentCategories = ProductCategory::find()
            ->select('id')
            ->where(['parentId' => $this->category]);

        if ($this->category !== null) {
            $query->joinWith('categories')
                ->andFilterWhere([
                    'product_to_category.categoryId' => $this->category
                ])
                ->orFilterWhere(
                    ['product_to_category.categoryId' => $queryParentCategories]
                );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'dtUpdate' => $this->dtUpdate,
//            'dtCreate' => $this->dtCreate,
            'product.status' => $this->status,
            'vendorCode' => $this->vendorCode,
            'backCode' => $this->backCode,
        ]);


//        $query->andWhere(['product.id' => '2']);


        foreach ($titleArray as $titleItem) {
            $query->andFilterWhere(['like', 'product.title', $titleItem]);
            //$query->orFilterWhere('title REGEXP "(Р|р|P|p)(О|о|O|o)(С|с|C|c)(С|с|C|c)ия" or match(title) against("Роccия")');
        }
//        dump($this->title);
//            ->andFilterWhere(['like', 'content', $this->content]);
//        dump($dataProvider->models);
        $models = $dataProvider->models;
        return $dataProvider;
    }
}
