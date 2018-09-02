<?php

namespace app\models\session;

use app\system\db\ActiveRecordSession;

class OrderContentSession extends ActiveRecordSession
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'productId'], 'integer'],
            [['productData'], 'string'],
            [['count', 'priceValue', 'discountValue'], 'double'],
        ];
    }
}