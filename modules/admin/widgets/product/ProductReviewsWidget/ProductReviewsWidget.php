<?php

namespace app\modules\admin\widgets\product\ProductReviewsWidget;

use app\models\db\ProductAnalogue;
use app\models\db\ProductReview;
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
class ProductReviewsWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductReviewsAsset::register($view);

        $dataProvider = new ActiveDataProvider([
            'query' => ProductReview::find()
                ->where(['productId' => $this->model->id]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
}