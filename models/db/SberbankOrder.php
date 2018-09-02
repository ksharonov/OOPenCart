<?php

namespace app\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "sberbank_order".
 *
 * @property integer $id
 * @property integer $orderId
 * @property string $sberbankOrderId
 * @property string $formUrl
 */
class SberbankOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sberbank_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId'], 'integer'],
            [['sberbankOrderId'], 'string', 'max' => 128],
            [['formUrl'], 'string', 'max' => 256],
            [['dtUpdate', 'dtCreate'], 'safe']
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
            'sberbankOrderId' => 'Sberbank Order ID',
            'formUrl' => 'Form Url',
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
                'updatedAtAttribute' => 'dtUpdate',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
