<?php

namespace app\models\traits;

use Yii;
use app\models\db\OneCCard;
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
trait OneCUserTrait
{
    /**
     * Возвращает бонусную карту
     *
     * @return LexemaCard|array|null|ActiveRecord
     */
    public function getOneCCard($number = null)
    {
        $card = OneCCard::find()
            ->andWhere(['type' => LexemaCard::TYPE_BONUSES]);

        if ($number) {
            $card = $card->andFilterWhere(['number' => $number]);
        } else {
            $card = $card->andFilterWhere(['like', 'phone', $this->phone]);
        }

        if (is_null($this->phone) && is_null($number)) {
            return null;
        }

        return $card->one() ?? null;
    }

    /**
     * Возвращает скидочную карту
     *
     * @return LexemaCard|array|null|ActiveRecord
     */
    public function getOneCDiscountCard($number = null)
    {
        if ($number && Yii::$app->user->identity) {
            $card = OneCCard::findOne(['number' => $number]);
            if (!$card) {
                $card = new OneCCard();
                $card->userId = Yii::$app->user->identity->id;
                $card->type = OneCCard::TYPE_DISCOUNT;
                $card->number = $number;
                $card->discountValue = $card->getDiscountValueFromOneC(); //kmplx_mega/hs/skidka?nomerkarty=1704822
                $card->save();
            }
        }

        $card = OneCCard::find()
            ->andWhere(['type' => OneCCard::TYPE_DISCOUNT])
            ->andWhere(['userId' => $this->id]);

        if ($number) {
            $card = $card->andFilterWhere(['number' => $number]);
        }

        return $card->one() ?? null;
    }
}