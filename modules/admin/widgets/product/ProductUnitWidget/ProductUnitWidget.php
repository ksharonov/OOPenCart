<?php

namespace app\modules\admin\widgets\product\ProductUnitWidget;

use app\models\db\ProductUnit;
use app\models\search\UnitSearch;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\admin\widgets\product\ProductUnitWidget\ProductUnitAsset;

/**
 * Виджет для добавления ЕИ в продукт
 *
 * @property integer $model
 * @property array $params
 */
class ProductUnitWidget extends Widget
{

    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductUnitAsset::register($view);

        $unitSearchModel = new UnitSearch();
        $unitProvider = $unitSearchModel->search(Yii::$app->request->queryParams);
        $unitProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => ProductUnit::find()
                ->where(['productId' => $this->model->id]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
            'unitProvider' => $unitProvider,
            'unitSearchModel' => $unitSearchModel
        ]);
    }

}