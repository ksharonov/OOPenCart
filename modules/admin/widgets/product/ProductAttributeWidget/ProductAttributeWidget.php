<?php

namespace app\modules\admin\widgets\product\ProductAttributeWidget;

use app\models\db\ProductFilterFastParam;
use app\modules\admin\widgets\product\ProductAttributeWidget\assets\FilterFastRelationAsset;
use app\modules\admin\widgets\product\ProductAttributeWidget\assets\ProductRelationAsset;
use Yii;
use app\models\db\ProductAttribute;
use app\models\search\ProductAttributeSearch;
use app\models\db\ProductToAttribute;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\admin\widgets\product\ProductAttributeWidget\ProductAttributeAsset;

/**
 * Виджет для добавления атрибутов в продукт
 *
 * @property integer $model
 * @property array $params
 */
class ProductAttributeWidget extends Widget
{
    public $relationClass = 'app\models\db\ProductToAttribute';

    public $relationPrimaryId = 'productId';

    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductAttributeAsset::register($view);

        $this->assetSelector();

        $attributeSearchModel = new ProductAttributeSearch();
        $attributesProvider = $attributeSearchModel->search(Yii::$app->request->queryParams);
        $attributesProvider->query->with(['group']);
        $attributesProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => $this->relationClass::find()
                ->innerJoin('product_attribute')
                ->where([$this->relationPrimaryId => $this->model->id])
                ->with(['attr', 'attr.group'])
                ->orderBy('attributeGroupId DESC'),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'relationClass' => $this->relationClass,
            'relationPrimaryId' => $this->relationPrimaryId,
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
            'attributesProvider' => $attributesProvider,
            'attributeSearchModel' => $attributeSearchModel
        ]);
    }

    public function assetSelector()
    {
        if ($this->relationClass == ProductToAttribute::className()) {
            ProductRelationAsset::register($this->getView());
        } elseif ($this->relationClass == ProductFilterFastParam::className()) {
            FilterFastRelationAsset::register($this->getView());
        }
    }
}