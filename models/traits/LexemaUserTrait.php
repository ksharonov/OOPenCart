<?php

namespace app\models\traits;

use app\models\session\OrderSession;
use yii\db\ActiveQuery;
use app\models\db\LexemaCard;

use yii\db\ActiveRecord;

/**
 * Class LexemaUserTrait
 * @package app\models\traits
 *
 * @method ActiveQuery hasOne($class, array $link)
 *
 * todo если честно я не уверен, что тут трейт нужен, но
 */
trait LexemaUserTrait
{
    /**
     * Возвращает бонусную карту
     *
     * @return LexemaCard|array|null|ActiveRecord
     */
    public function getLexemaCard($number = null)
    {
        $card = LexemaCard::find()
            ->andWhere(['type' => LexemaCard::TYPE_BONUSES]);

        if ($number) {
            $card = $card->andFilterWhere(['number' => $number]);
        } else {
            $card = $card->andFilterWhere(['like', 'phone', $this->phone]);
        }

        if (is_null($this->phone) && is_null($number)){
            return null;
        }

        return $card->one() ?? null;
    }

    /**
     * Возвращает скидочную карту
     *
     * @return LexemaCard|array|null|ActiveRecord
     */
    public function getLexemaDiscountCard($number = null)
    {
        $card = LexemaCard::find()
            ->andWhere(['type' => LexemaCard::TYPE_DISCOUNT]);

        if ($number) {
            $card = $card->andFilterWhere(['number' => $number]);
        } else {
            $card = $card->andFilterWhere(['like', 'phone', $this->phone]);
        }

        if (is_null($this->phone) && is_null($number)){
            return null;
        }

        return $card->one() ?? null;
    }
}