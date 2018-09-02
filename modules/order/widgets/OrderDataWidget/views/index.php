<?php
use yii\widgets\Pjax;

/* @var \app\models\db\Order $order */
//dump($order);

Pjax::begin(['timeout' => 10000, 'id' => 'orderData', 'enablePushState' => false]);
echo "Контактная инфа";
dump($order->comment);

echo "инфа по доставке";
dump($order->deliveryData);
dump($order->deliveryMethod);

echo "инфа по оплате";
dump($order->paymentData);
dump($order->paymentMethod);

Pjax::end();