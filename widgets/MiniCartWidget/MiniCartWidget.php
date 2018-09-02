<?php

namespace app\widgets\MiniCartWidget;

use Yii;
//use yii\base\Widget;
use app\system\base\Widget;
use app\widgets\MiniCartWidget\MiniCartAsset;
use yii\helpers\Json;
//use app\helpers\CartHelper;
use app\models\db\Product;

/**
 * Виджет маленькой корзины для шапки с выпадающим списком
 * Внешний вид позже, пока тесты
 *
 * @property Product $model
 * @property array $params
 */
class MiniCartWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        MiniCartAsset::register($view);

        $cart = Yii::$app->cart->get();
//        $total = CartHelper::createCartTotal();

        return $this->render('index', [
            'cart' => $cart, //Корзина пока не используется
//            'total' => $total
        ]);
    }
}