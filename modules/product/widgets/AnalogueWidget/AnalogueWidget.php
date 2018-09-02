<?php

namespace app\modules\product\widgets\AnalogueWidget;

use yii\base\Widget;
use app\models\db\Product;

/**
 * Виджет сопутствующих товаров в товаре
 * @property Product $model
 */
class AnalogueWidget extends Widget
{
    public $model = null;

    public function run()
    {

        $data = $this->model->productAnalogues;

        return $this->render('index', [
            'model' => $this->model,
            'data' => $data
        ]);
    }

}