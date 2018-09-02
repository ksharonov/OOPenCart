<?php

namespace app\modules\cart\widgets\ElementCartWidget;

use app\components\CartComponent;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\cart\widgets\ElementCartWidget\ElementCartAsset;
use app\models\search\ProductPriceGroupSearch;
use app\models\db\ProductPrice;
use yii\helpers\Json;
use yii\web\Cookie;
use app\models\db\Product;

/**
 * Виджет корзины
 *
 * @property Product $model
 * @property array $params
 */
class ElementCartWidget extends Widget
{
    public $model = null;

    public $params = [];

    public function run()
    {
        $view = $this->getView();
        ElementCartAsset::register($view);

        /** @var CartComponent $cartComponent */
        $cartComponent = Yii::$app->cart;
        $cart = $cartComponent->get();

        return $this->render('index', [
            'cart' => $cart
        ]);
    }
}