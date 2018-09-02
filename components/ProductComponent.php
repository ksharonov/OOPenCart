<?php

namespace app\components;

use yii\base\Component;
use yii\helpers\Html;
use app\models\db\Product;

/**
 * Class ProductComponent
 *
 * Тестовый компонент продукта
 *
 * @package app\components
 */
class ProductComponent extends Component
{
    public $product = null;

    public function init()
    {
        parent::init();
    }

    /**
     * @param null $id
     */
    public function get($id = null)
    {
        $product = Product::findOne($id);
        //dump($product);

    }
}