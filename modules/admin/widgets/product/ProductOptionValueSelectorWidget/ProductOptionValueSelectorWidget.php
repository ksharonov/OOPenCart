<?php

namespace app\modules\admin\widgets\product\ProductOptionValueSelectorWidget;

use app\models\db\Product;
use app\models\db\ProductOption;
use app\models\db\ProductOptionValue;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * Виджет для выбора значений в опциях товара
 *
 * @property ProductOption $productOption
 * @property Product $product
 * @property ProductOptionValue[] $productOptionValues
 */
class ProductOptionValueSelectorWidget extends Widget
{
    public $product;
    public $productOption = null;
    public $productOptionValues = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductOptionValueSelectorAsset::register($view);
        return $this->render('index', [
            'productOption' => $this->productOption,
            'productOptionValues' => $this->productOptionValues,
            'product' => $this->product
        ]);
    }
}