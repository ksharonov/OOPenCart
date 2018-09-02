<?php

namespace app\modules\admin\widgets\product\ProductAnalogueWidget;

use app\models\db\ProductAnalogue;
use app\models\search\ProductSearch;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\models\db\Product;
use app\modules\admin\widgets\product\ProductAnalogueWidget\ProductAnalogueAsset;

/**
 * Виджет для добавления аналогов продуктов
 *
 * @property Product $model
 * @property array $params
 */
class ProductAnalogueWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductAnalogueAsset::register($view);


        $productSearchModel = new ProductSearch();
        $productProvider = $productSearchModel->search(Yii::$app->request->queryParams);
        $productProvider->query->where(['<>', 'id', $this->model->id]);
        $productProvider->pagination->pageSize = 10;
        //mail(1,1,1); //Какого хуя?))
        $dataProvider = new ActiveDataProvider([
            'query' => ProductAnalogue::find()
                ->with(['product', 'productAnalogue'])
                ->where(['productId' => $this->model->id])
                ->andWhere(['<>', 'productAnalogueId', $this->model->id]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'productModel' => $this->model,
            'productProvider' => $productProvider,
            'productSearchModel' => $productSearchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}