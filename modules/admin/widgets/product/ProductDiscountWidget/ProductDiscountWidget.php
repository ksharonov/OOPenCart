<?php

namespace app\modules\admin\widgets\product\ProductDiscountWidget;

use app\models\db\Discount;
use app\models\search\DiscountSearch;
use Yii;
use app\modules\admin\widgets\product\ProductDiscountWidget\ProductDiscountAsset;
use app\widgets\ProductListWidget\modes\ProductSearchDiscount;
use yii\data\ActiveDataProvider;

class ProductDiscountWidget extends \yii\base\Widget
{

    /**
     * @var null
     */
    public $relModel = null;

    /**
     * @var null
     */
    public $relModelId = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductDiscountAsset::register($view);


        $productSearchModel = new DiscountSearch();
        $productProvider = $productSearchModel->search(Yii::$app->request->queryParams);

        $productProvider->query
            ->where(['relModel' => $this->relModel])
            ->andWhere(['relModelId' => $this->relModelId]);

        $productProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => Discount::find()
                ->where(['relModel' => $this->relModel])
                ->andWhere(['relModelId' => $this->relModelId]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        $formModel = new Discount();
        $formModel->relModel = $this->relModel;
        $formModel->relModelId = $this->relModelId;

        return $this->render('index', [
            'formModel' => $formModel,
            'relModel' => $this->relModel,
            'productProvider' => $productProvider,
            'productSearchModel' => $productSearchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}