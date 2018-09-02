<?php

namespace app\modules\admin\widgets\product\ProductPriceWidget;

use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\admin\widgets\product\ProductPriceWidget\ProductPriceAsset;
use app\models\search\ProductPriceGroupSearch;
use app\models\db\ProductPrice;
use app\models\db\Product;

/**
 * Виджет для добавления цен в продукт
 *
 * @property Product $model
 * @property array $params
 */
class ProductPriceWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductPriceAsset::register($view);

        $priceSearchModel = new ProductPriceGroupSearch();
        $priceProvider = $priceSearchModel->search(Yii::$app->request->queryParams);
        $priceProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => ProductPrice::find()
                ->where(['productId' => $this->model->id])
                ->andWhere('popId is NULL')
                ->orderBy('productPriceGroupId DESC'),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
            'priceProvider' => $priceProvider,
            'priceSearchModel' => $priceSearchModel
        ]);
    }
}