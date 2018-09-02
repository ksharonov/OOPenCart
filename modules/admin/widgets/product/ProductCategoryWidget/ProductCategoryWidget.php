<?php

namespace app\modules\admin\widgets\product\ProductCategoryWidget;

use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\search\ProductCategorySearch;
use app\models\db\ProductToCategory;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\admin\widgets\product\ProductCategoryWidget\ProductCategoryAsset;
use Yii;

/**
 * Виджет для добавления категорий в продукт
 *
 * @property Product $model
 * @property array $params
 */
class ProductCategoryWidget extends Widget
{
    public $model = null;

    public $params = [];

    public $relationModel = 'ProductToCategory';

    public $relationId = 'productId';

    public function run()
    {
        $view = $this->getView();
        ProductCategoryAsset::register($view);

        $categorySearchModel = new ProductCategorySearch();
        $categoryProvider = $categorySearchModel->search(Yii::$app->request->queryParams);
        $categoryProvider->query->with(['parent']);
        $categoryProvider->query->orderBy('parentId ASC');
        $categoryProvider->pagination->pageSize = 10;

        $modelClass = "app\\models\\db\\" . $this->relationModel;

        $dataProvider = new ActiveDataProvider([
            'query' => $modelClass::find()
                ->where([$this->relationId => $this->model->id])
                ->orderBy('categoryId DESC'),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
            'categoryProvider' => $categoryProvider,
            'categorySearchModel' => $categorySearchModel,
            'relationModel' => $this->relationModel,
            'relationId' => $this->relationId
        ]);
    }
}