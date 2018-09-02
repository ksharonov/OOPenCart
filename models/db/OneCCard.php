<?php

namespace app\models\db;

use app\helpers\StringHelper;
use app\system\base\OneCLoader;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "one_c_card".
 *
 * @property int $id
 * @property int $number Номер карты
 * @property int $type Тип карты
 * @property int $discountValue Размер скидки
 * @property int $userId Пользователь
 */
class OneCCard extends \yii\db\ActiveRecord
{
    /**
     * Скидочная карта
     */
    const TYPE_DISCOUNT = 47175;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'one_c_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'type', 'discountValue', 'userId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'discountValue' => 'Discount Value',
            'userId' => 'User ID',
        ];
    }

    public function getDiscountValueFromOneC()
    {
        if (!$this->discountValue) {
            $loader = new OneCLoader();
            $loader->source = 'skidka?nomerkarty=' . $this->number;
            $result = $loader->load();
            if (isset($result['skidka']) && $result['skidka'] > 0) {
                $this->discountValue = $result['skidka'] ?? 0;
                $this->save(false);
            }
        }
        return $this->discountValue;
    }
}
