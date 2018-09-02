<?php

namespace app\models\base;

use app\components\ClientComponent;
use app\models\cookie\CartCookie;
use Yii;
//use yii\base\Model;
use app\system\base\Model;
use app\models\db\Product;
use app\models\db\User;
use yii\behaviors\TimestampBehavior;

/**
 * Class Cart
 *
 * Элемент корзины
 *
 * @package app\models\system
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $count
 * @property integer $userId
 * @property Product $product
 * @property User $user
 * @property integer $price
 * @property integer $sum
 */
class CartItem extends Model
{
    public $cartId;
    public $productId;
    public $count;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'productId',  'userId'], 'integer'],
            [['count'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Продукт',
            'count' => 'Количество',
            'userId' => 'Клиент'
        ];
    }

    /**
     * Действия после загрузки данных
     * @return void
     */
    public function afterLoad()
    {
        $balance = $this->product->balance ?? 0;

        /** @var ClientComponent $client */
        $client = Yii::$app->client;

        if ($client->isIndividual() && $this->count > $balance) {
            $this->count = $balance;
        }


        if ($this->price == null || $this->price == 0) {
            $this->count = 0;
        }
//        dump($this->count);
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
//        dump($this->productId);
        return Product::findOne($this->productId);
    }

    /**
     * Цена товара
     *
     * @return integer
     */
    public function getPrice()
    {
//        dump([
//            $this->product->id ?? null,
//            $this->product->price->value ?? null
//        ]);
        $product = $this->product;

        if ($product !== null) {
            return $product->price->value;
        }

        return null;
    }

    /**
     * Сумма товара
     *
     * @return int
     */
    public function getSum()
    {
        return $this->price * $this->count;
    }


}