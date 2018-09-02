<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 18-12-2017
 * Time: 9:50 AM
 */

namespace app\modules\admin\widgets\product\ProductAssociatedWidget;

use app\models\db\ProductAssociated;
use app\models\search\ProductSearch;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\models\db\Product;


/**
 * Виджет для добавления аналогов продуктов
 *
 * @property Product $model
 * @property array $params
 */
class ProductAssociatedWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductAssociatedAsset::register($view);

        $productSearchModel = new ProductSearch();
        $productProvider = $productSearchModel->search(Yii::$app->request->queryParams);
        $productProvider->query->where(['<>', 'id', $this->model->id]);
        $productProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => ProductAssociated::find()
                ->where(['productId' => $this->model->id])
                ->andWhere(['<>', 'productAssociatedId', $this->model->id]),
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