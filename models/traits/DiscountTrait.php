<?php

namespace app\models\traits;

use app\components\ClientComponent;
use app\models\db\Discount;
use yii\db\ActiveQuery;

/**
 * Class DiscountTrait
 * @package app\models\traits
 *
 * Функционал скидок
 *
 * @property int $relModel
 * @method ActiveQuery hasMany($class, array $link)
 * @method ActiveQuery hasOne($class, array $link)
 * @property Discount $discount
 * @property Discount[] $discounts
 * @property boolean $discountValue
 *
 * @deprecated
 */
trait DiscountTrait
{
    /**
     * Активные скидки
     * @return ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => $this->relModel,
                'status' => Discount::STATUS_ACTIVE
            ]);
    }
}