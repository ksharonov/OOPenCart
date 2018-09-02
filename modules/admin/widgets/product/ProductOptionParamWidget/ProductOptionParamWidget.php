<?php

namespace app\modules\admin\widgets\product\ProductOptionParamWidget;

use app\models\db\Product;
use app\models\db\ProductOptionParam;
use app\models\search\ProductOptionParamSearch;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Виджет для добавления вариантов в продукт
 *
 * @property Product $model
 * @property array $params
 */
class ProductOptionParamWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductOptionParamAsset::register($view);

        $optionParamSearchModel = new ProductOptionParamSearch();
        $optionParamProvider = $optionParamSearchModel->search(Yii::$app->request->queryParams);
        $optionParamProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => ProductOptionParam::find()
                ->where(['productId' => $this->model->id]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
            'optionParamProvider' => $optionParamProvider,
            'optionParamSearchModel' => $optionParamSearchModel,
        ]);
    }
}