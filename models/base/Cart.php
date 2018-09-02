<?php

namespace app\models\base;

use Yii;
use app\system\base\Model;
//use yii\base\Model;
use app\models\db\User;

/**
 * Class Cart
 *
 * Класс корзины
 *
 * @package app\models\system
 *
 * @property integer $id
 * @property CartItem[] $items
 * @property integer $sum
 * @property integer $count
 * @property integer $userId
 */
class Cart extends Model
{

    /**
     * @var integer id-корзины (пока не используется)
     */
    public $id;

    /**
     * @var array содержимое корзины
     */
    public $items = [];

    /**
     * @var integer id-клиента
     */
    public $userId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sum'], 'integer'],
            [['items'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'items' => 'Содержимое',
            'sum' => 'Сумма',
            'userId' => 'Клиент'
        ];
    }

    /**
     * Действия после загрузки данных
     * @return void
     */
    public function afterLoad()
    {

    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity ?? null;

        $this->userId = $user->id ?? null;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return User::findOne($this->userId);
    }


    /**
     * Сумма всей корзины
     *
     * @return int
     */
    public function getSum(): float
    {
        $sum = 0;
        $items = $this->items;

        foreach ($items as $item) {
            $sum += $item->price * $item->count;
        }

        return $sum;
    }

    /**
     * todo всё-таки задуматься о едином механизме получения скидок (уже есть в OrderSession)
     */
    public function getFinalSum()
    {
        $sum = 0;

        $cart = \Yii::$app->cart->get();

        foreach ($cart->items as $item) {
            $discount = $item->product->discount->priority->value ?? 0;
            $sum += $item->price * $item->count;
            if (!$discount && Yii::$app->user->identity && Yii::$app->user->identity->lexemaDiscountCard) {
                $discount = (($item->price * $item->count) / 100 * Yii::$app->user->identity->lexemaDiscountCard->discountValue) ?? 0;
            }
//            dump([$item->price, $discount]);
            $sum -= $discount;
        }


        return $sum;
    }

    public function getDiscount()
    {
        return ($this->getSum() - $this->getFinalSum()) ?? null;
    }

    /**
     * Количество всех позиций товаров в корзине
     *
     * @return int
     */
    public function getCount(): int
    {
        $count = 0;
        $items = $this->items;

        foreach ($items as $item) {
            $count += $item->count;
        }

        return $count;
    }


}