<?php

namespace app\models\session;

use app\models\db\LexemaCard;
use Yii;
use app\models\base\order\OrderDelivery;
use app\models\base\order\OrderPayment;
use app\models\db\Order;
use app\models\traits\OrderTrait;
use app\system\db\ActiveRecordSession;

/**
 * Class OrderSession
 * @package app\models\session
 * @property integer $id
 * @property Order $order
 * @property array $addCosts
 * @property array $discountData
 */
class OrderSession extends ActiveRecordSession
{
    use OrderTrait;

    const STATE_DELIVERY = 1;
    const STATE_CONTACTS = 2;
    const STATE_PAYMENT = 3;
    const STATE_FINAL = 4;

    public $state = null;

    public $error = null;

    public $defaultParamAttributes = [
        'addCosts',
        'discountData'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientId', 'userId', 'addressId', 'source', 'paymentMethod', 'deliveryMethod'], 'integer'],
            [['dtUpdate', 'dtCreate', 'userData', 'deliveryData', 'paymentData', 'order', 'user', 'addCosts', 'discountData', 'cardData'], 'safe'],
            [['comment', 'addressData', 'clientData'], 'string'],
        ];
    }

    /**
     * Получение заказа
     *
     * @return Order | null
     */
    public function getOrder()
    {
        if (!isset($this->id)) {
            return null;
        }

        $id = $this->id;

        $order = Order::findOne($id);

        return $order;
    }

    /**
     * Установить дополнительные затраты
     * @param $title
     * @param $cost
     */
    public function setAddCost($title, $cost)
    {
        if (!is_array($this->addCosts)) {
            $this->addCosts = [];
        }

        $this->addCosts = array_merge($this->addCosts, [$title => $cost]);
        $this->save();
    }

    /**
     * Установить скидку на заказ
     * @param $title
     * @param $cost
     */
    public function setDiscount($title, $cost)
    {
        if (!is_array($this->discountData)) {
            $this->discountData = [];
        }

        $this->discountData = array_merge($this->discountData, [$title => $cost]);
        $this->save();
    }

    public function getContent()
    {

    }

    /**
     * 2018 Всё смешалось: люди, кони...
     * Простите, тут смешались подсчёты скидок для карт Лексемы и 1С. Если хватит времени, то сделаю по уму.
     * @return float|int|mixed
     */
    public function getFinalSum()
    {
        $sum = 0;
        $useCard = $this->order->cardData['use'] ?? false;
        $lexemaCardNumber = $this->order->cardData['number'] ?? null;
        $discountCard = false;

        if ($useCard && $lexemaCardNumber) {
            $lexemaCard = LexemaCard::findByNumber($lexemaCardNumber);
            if ($lexemaCard && $lexemaCard->type == LexemaCard::TYPE_DISCOUNT) {
                $discountCard = $lexemaCard;
            }
        }

        $cart = \Yii::$app->cart->get();

        foreach ($cart->items as $item) {
            $discount = $item->product->discount->priority->value ?? 0;

            $sum += $item->price * $item->count;

            if (!$discount && Yii::$app->user->identity && Yii::$app->user->identity->getLexemaDiscountCard($lexemaCardNumber)) {
                $discount = (($item->price * $item->count) / 100 * Yii::$app->user->identity->getLexemaDiscountCard($lexemaCardNumber)->discountValue) ?? 0;
            }

            if (!$discount && Yii::$app->user->identity && Yii::$app->user->identity->getOneCDiscountCard($lexemaCardNumber)) {
                $discount = (($item->price * $item->count) / 100 * Yii::$app->user->identity->getOneCDiscountCard($lexemaCardNumber)->discountValue) ?? 0;
            }

            $sum -= $discount;
        }

        if (is_array($this->discountData)) {
            foreach ($this->discountData as $key => $discountValue) {
                $sum -= $discountValue;
            }
        }

        if (is_array($this->addCosts)) {
            foreach ($this->addCosts as $key => $costValue) {
                $sum += $costValue;
            }
        }

        if (is_array($this->cardData) && isset($this->cardData['value']) && $this->cardData['use']) {
            $sum -= $this->cardData['value'];
        }


        return $sum;

    }
}