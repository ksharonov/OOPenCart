<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;
use app\models\db\Storage;
use app\models\db\ProductOptionParam;

/**
 * This is the model class for table "storage_balance".
 *
 * Модель таблицы остатков по складам
 *
 * @property integer $id
 * @property integer $storageId
 * @property integer $productId
 * @property integer $quantity
 * @property integer $popId
 * @property Product $product
 * @property ProductOptionParam $productOptionParam
 * @property Storage $storage
 * @property integer $state
 * @property string $dtReceipt
 * @property string $daysToStock
 */
class StorageBalance extends \app\system\db\ActiveRecord
{
    const STATE_IN_STOCK = 0;
    const STATE_WAIT = 1;

    public static $states = [
        self::STATE_IN_STOCK => 'В наличии',
        self::STATE_WAIT => 'В ожидании'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage_balance';
    }

    public function beforeSave($insert)
    {
        if (!$this->dtReceipt) {
            $this->state = self::STATE_IN_STOCK;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storageId', 'productId', 'popId', 'state'], 'integer'],
            [['dtReceipt'], 'safe'],
            [['quantity'], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'storageId' => 'Склад',
            'productId' => 'Продукт',
            'quantity' => 'Количество',
            'popId' => 'Сборка',
            'state' => 'Состояние',
            'dtReceipt' => 'Дата поступления'
        ];
    }

    /**
     * Получить продукт
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Получить набор
     *
     * @return ActiveQuery
     */
    public function getProductOptionParam()
    {
        return $this->hasOne(ProductOptionParam::className(), ['id' => 'popId']);
    }

    /**
     * Получить склад
     *
     * @return ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storageId']);
    }

    /**
     * Дней до товара в наличии
     */
    public function getDaysToStock()
    {
        $nowDateModel = new \DateTime();
        $nextDateModel = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtReceipt);
        if (!$nextDateModel){
            return null;
        }
        $interval = date_diff($nowDateModel, $nextDateModel);

        return $interval->days;
    }

    /**
     * В наличии
     * @return bool
     */
    public function getInStock()
    {
        return $this->state === self::STATE_IN_STOCK;
    }

    public function getQuantity()
    {
        return $this->quantity + 0;
    }
}
