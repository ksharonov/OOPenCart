<?php

namespace app\modules\product\widgets\AssociatedWidget;

use yii\base\Widget;
use app\models\db\Product;

/**
 * Виджет сопутствующих товаров в товаре
 * @property Product $model
 */
class AssociatedWidget extends Widget
{
    public $model = null;

    public function run()
    {

        $data = $this->model->productAssocs;

        return $this->render('index', [
            'model' => $this->model,
            'data' => $data
        ]);
    }

}