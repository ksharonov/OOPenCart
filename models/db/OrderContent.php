<?php

namespace app\models\db;

use app\system\behaviors\TextJsonColumnsBehavior;
use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;
use app\models\db\Order;
use yii\helpers\Json;

/**
 * This is the model class for table "order_content".
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $productId
 * @property float $priceValue
 * @property float $count
 * @property string $productData
 * @property Product $product
 * @property Order $order
 * @property integer | float $sum
 * @property float $vat
 * @property float|int|mixed $sumWVat
 * @property bool $fromRemote
 * @property float $discountValue
 */
class OrderContent extends \app\system\db\ActiveRecord
{
    public $fromRemote = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'productId'], 'integer'],
            [['productData'], 'string'],
            [['count', 'priceValue', 'discountValue'], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Order ID',
            'productId' => 'Product ID',
            'priceValue' => 'Цена',
            'count' => 'Количество',
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => TextJsonColumnsBehavior::className(),
//                'attributes' => ['productData']
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->fromRemote == false) {
            $this->priceValue = (float)$this->product->price->value ?? null;
            $lexemaCardNumber = $this->order->cardData['number'] ?? null;

            if (Yii::$app->client->isIndividual()) {
                $this->discountValue = $this->product->discount->priority->value ?? null;
                $this->discountValue = $this->product->discount->getValue($this->priceValue);

                if ($this->discountValue > 0) {
                    $this->discountValue = (float)$this->discountValue;
                }

                if (!$this->discountValue && Yii::$app->user->identity && Yii::$app->user->identity->getLexemaDiscountCard($lexemaCardNumber)) {
                    $this->discountValue = ($this->priceValue / 100 * Yii::$app->user->identity->getLexemaDiscountCard($lexemaCardNumber)->discountValue) ?? 0;
                }
            }
        }

        $this->productData = Json::encode($this->product->data);
        return parent::beforeSave($insert);
    }

    /**
     * Возвращает заказ
     *
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'orderId']);
    }

    /**
     * Возвращает продукт заказа
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /** Сумма
     * @return float|int
     */
    public function getSum()
    {
        $sum = 0;

        $sum = $this->priceValue * $this->count;

        return $sum;
    }

    /** НДС
     * @return float
     */
    public function getVat()
    {
        return 0.13 * $this->sum;
    }

    /** Сумма без НДС
     * @return float|int|mixed
     */
    public function getSumWVat()
    {
        return $this->sum - $this->vat;
    }

    /**
     * Получить название товара
     */
    public function getTitle()
    {
        $productData = Json::decode($this->productData, false);
        return $productData->title ?? $this->product->title ?? null;
    }
}
