<?php

namespace app\models\cookie;

use app\models\db\Product;
use app\system\db\ActiveRecordCookie;

/**
 * Class CartCookie
 * @package app\models\cookie
 * @property array $products
 */
class CartCookie extends ActiveRecordCookie
{
    public function rules()
    {
        return [
            ['products', 'safe']
        ];
    }
}