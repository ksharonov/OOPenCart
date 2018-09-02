<?php

namespace app\modules\admin\widgets\product\ProductImageWidget;

use Yii;
use yii\base\Widget;
use app\modules\admin\widgets\product\ProductImageWidget\ProductImageAsset;

/**
 * Виджет для изображений
 *
 * @property integer $model
 * @property array $params
 */
class ProductImageWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ProductImageAsset::register($view);

        return $this->render('index', [
            'productModel' => $this->model
        ]);
    }
}