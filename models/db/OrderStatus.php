<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property integer $isHidden
 * @static array $types
 */
class OrderStatus extends \app\system\db\ActiveRecord
{
    const TYPE_ORDER = 0;
    const TYPE_PAYMENT = 1;
    const TYPE_DELIVERY = 2;

    public static $types = [
        self::TYPE_ORDER => 'Заказ',
        self::TYPE_PAYMENT => 'Оплата',
        self::TYPE_DELIVERY => 'Доставка'
    ];

    public static $defaultStatuses = [

    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'isHidden'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'type' => 'Тип',
            'isHidden' => 'Скрытый'
        ];
    }
}
