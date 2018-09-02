<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Order;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "order_status_history".
 *
 * @property integer $id
 * @property integer $orderId
 * @property integer $orderStatusId
 * @property string $dtCreate
 * @property Order $order
 * @property OrderStatus $status
 */
class OrderStatusHistory extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'orderStatusId'], 'integer'],
            [['dtCreate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderId' => 'Заказ',
            'orderStatusId' => 'Статус',
            'dtCreate' => 'Дата создания',
            'order' => 'Заказ',
            'status' => 'Статус'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dtCreate',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()')
            ],
        ];
    }

    /**
     * Возвращает заказ
     *
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), [
            'id' => 'orderId'
        ]);
    }

    /**
     * Возвращает статус
     *
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(OrderStatus::className(), [
            'id' => 'orderStatusId'
        ]);
    }
}
