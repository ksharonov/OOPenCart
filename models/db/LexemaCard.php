<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "lexema_card".
 *
 * @property integer $id
 * @property integer $phone
 * @property string $fio
 * @property string $number
 * @property integer $type
 * @property integer $bonuses
 * @property integer $amountPurchases
 * @property float $discountValue
 */
class LexemaCard extends \yii\db\ActiveRecord
{
    /**
     * Ценовая карта
     */
    const TYPE_PRICE = 47176;

    /**
     * Скидочная карта
     */
    const TYPE_DISCOUNT = 47175;

    /**
     * Бонусная карта
     */
    const TYPE_BONUSES = 47174;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lexema_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'phone'], 'integer'],
            [['bonuses', 'amountPurchases', 'discountValue'], 'double'],
            [['number'], 'string', 'max' => 64],
            [['fio'], 'string', 'max' => 256],
            [['number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Номер карты',
            'type' => 'Тип карты',
            'bonuses' => 'Бонусы',
            'amountPurchases' => 'Amount Purchases',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'discountValue' => 'Скидка'
        ];
    }

    /**
     * @param $number
     * @return LexemaCard|array|null|\yii\db\ActiveRecord
     */
    public static function findByNumber($number)
    {
        return self::find()
            ->where(['number' => $number])
            ->one();
    }
}
