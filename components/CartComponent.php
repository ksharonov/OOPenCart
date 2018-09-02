<?php

namespace app\components;

use app\helpers\ModelRelationHelper;
use app\models\base\cart\CartDiscount;
use app\models\cookie\CartCookie;
use app\models\db\Discount;
use app\models\db\Setting;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\base\Cart;
use app\models\base\CartItem;

/**
 * Class CartComponent
 * Компонент данных по корзине
 * @package app\components
 * @property int $sum
 * @property int $count
 * @property Discount $discount
 */
class CartComponent extends Component
{
    /**
     * Объект корзины
     * @var Cart
     */
    public $cart;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        if (!$this->cart) {
            $this->build();
        }

        parent::__construct($config);
    }

    /**
     * Сборка корзины в удобный вид
     *
     * @return Cart
     */
    public function build()
    {
        /** @var CartCookie $cartCookieModel */
        $cartCookieModel = CartCookie::get();

        $cartCookie = $cartCookieModel->products;

        $cartModel = new Cart();

        $cart = [];

        $maxCount = Setting::get('CART.MAX.COUNT.ITEMS');
        $count = 0;

        if ($cartCookie) {
            foreach ($cartCookie as $key => &$item) {
                if ($count < $maxCount) {
                    $cartItemModel = new CartItem();
                    $cartItemModel->load(['CartItem' => $item]);
                    if ($cartItemModel->count > 0) {
                        $cart[] = $cartItemModel;
                    } else {
                        unset($cartCookie[$key]);
                    }
                }

                $count++;
            }

            $cartModel->items = $cart;
        }

        $this->cart = $cartModel;
    }

    /**
     * Получить корзину
     *
     * @return Cart
     */
    public function get()
    {
        return $this->cart;
    }

    /**
     * Количество элементов в корзине
     * @return int
     */
    public function getCount()
    {
        return $this->cart->count;
    }

    /**
     * Общая сумма корзины
     * @return int
     */
    public function getSum()
    {
        return $this->cart->sum;
    }

    /**
     * Пуста ли корзина?
     * @return bool
     */
    public function isNull()
    {
        return ($this->cart->sum == 0 || $this->cart->count == 0);
    }

    /**
     * Очистка корзины
     */
    public function clear()
    {
        CartCookie::deleteAll();
    }

    /**
     * @return Discount|array|null|\yii\db\ActiveRecord
     */
    public function getDiscount()
    {
        $cartSum = \Yii::$app->cart->sum;

        $query = \app\models\db\Discount::find()
            ->orWhere([
                'AND',
                'relModel' => ModelRelationHelper::REL_MODEL_CART,
                ['<=', 'JSON_EXTRACT(params, "$.sum")', $cartSum]
            ])
            ->orderBy('priority DESC')
            ->one();

        return $query ?? null;
    }


}