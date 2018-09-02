<?php

namespace app\modules\product\widgets\ActionsWidget;

use yii\base\Widget;
use app\models\db\Product;

/**
 * Виджет кнопок добавления в корзину
 * @property Product $model
 */
class ActionsWidget extends Widget
{
    public $model = null;

    public function run()
    {

        return $this->render('index', [
            'model' => $this->model,
        ]);
    }

}