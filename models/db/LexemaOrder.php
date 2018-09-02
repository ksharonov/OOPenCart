<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "order_lexema".
 *
 * @property integer $id
 * @property string $orderNumber
 * @property integer $orderId
 * @property integer $vcode
 */
class LexemaOrder extends \app\system\db\ActiveRecord
{

    /**
     * Номер статуса отмены заказа
     */
    const CANCEL_CODE = 49521;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lexema_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'vcode'], 'integer'],
            [['orderNumber'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orderNumber' => 'Order Number',
            'orderId' => 'Order ID',
            'vcode' => 'Lexema Order Vcode',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'orderId']);
    }
}
