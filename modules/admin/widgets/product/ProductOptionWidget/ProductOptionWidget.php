<?php

namespace app\modules\admin\widgets\product\ProductOptionWidget;

use app\models\db\Product;
use app\models\search\ProductOptionParamSearch;
use app\models\search\ProductOptionSearch;
use app\models\db\ProductOption;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Виджет для добавления опций в продукт
 *
 * @property Product $model
 * @property array $params
 */
class ProductOptionWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductOptionAsset::register($view);

        $optionSearchModel = new ProductOptionSearch();
        $optionProvider = $optionSearchModel->search(Yii::$app->request->queryParams);
        $optionProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => $this->model->getOptions(),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        $allDataProvider = new ActiveDataProvider([
            'query' => ProductOption::find(),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'product' => $this->model,
            'dataProvider' => $dataProvider,
            'allDataProvider' => $allDataProvider,
            'optionProvider' => $optionProvider,
            'optionSearchModel' => $optionSearchModel,
        ]);
    }
}