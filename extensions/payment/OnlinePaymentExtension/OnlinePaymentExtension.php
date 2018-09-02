<?php

namespace app\extensions\payment\OnlinePaymentExtension;

use app\system\extension\PaymentExtension;
use app\system\interfaces\IPaymentExtension;

class OnlinePaymentExtension extends PaymentExtension implements IPaymentExtension
{
    public static function afterPaid(array $params = [])
    {
        return false;
    }
}