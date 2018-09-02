<?php

namespace app\modules\product\widgets\AttributesWidget;

use yii\base\Widget;
use app\helpers\AttributesHelper;
use app\models\db\Product;

/**
 * Виджет таблицы атрибутов в товаре
 * @property Product $model
 */
class AttributesWidget extends Widget
{
    public $model = null;

    public function run()
    {
        $data = AttributesHelper::createAttributesData($this->model);

        return $this->render('index', [
            'model' => $this->model,
            'data' => $data
        ]);
    }
}