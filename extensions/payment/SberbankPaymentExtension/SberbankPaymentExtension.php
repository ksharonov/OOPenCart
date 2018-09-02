<?php

namespace app\extensions\payment\SberbankPaymentExtension;

use app\extensions\payment\SberbankPaymentExtension\models\SberbankPaymentHandler;
use app\helpers\ChequeHelper;
use app\models\db\Order;
use app\models\db\SberbankOrder;
use app\models\db\Setting;
use app\models\session\OrderSession;
use app\system\interfaces\IPaymentExtension;
use yii\base\Widget;
use app\system\extension\PaymentExtension;

/**
 * Class TestPaymentExtension
 * Тестовый виджет оплаты
 * @package app\extensions\payment\TestPaymentExtension
 */
class SberbankPaymentExtension extends PaymentExtension implements IPaymentExtension
{

    const ORDER_STATUS_SUCCESS = 2;
    const ERROR_CODE_SUCCESS = 0;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->_view, [
            'fields' => $this->fields,
            'id' => $this->id,
            'extensionParams' => $this->extensionParams,
            'order' => $this->order
        ]);
    }

    /**
     * @param array $params
     * @return bool|object
     */
    public static function beforePaid(array $params = [])
    {
        $instance = self::getInstance();
        $callback = $params['callback'] ?? '/order/callback/';
        $finalSum = $params['finalSum'] ?? $instance->order->finalSum;

        $handler = new SberbankPaymentHandler($instance->params['url'], $instance->params['login'], $instance->params['password']);
        $result = $handler->register($finalSum * 100, $instance->order->id, Setting::get('SITE.URL') . $callback);
        /*
         * Почему так?
         * Боюсь, что могут добавиться другие данные в других расширениях + пока чтоб видеть это
         * PS Код с комментариями - непонятное зло(
         */
        if (!isset($result->orderId)) {
            return false;
        }

        $sberbankOrder = new SberbankOrder();
        $sberbankOrder->orderId = $instance->order->id;
        $sberbankOrder->sberbankOrderId = $result->orderId;
        $sberbankOrder->formUrl = $result->formUrl;
        $sberbankOrder->save();

        return (object)[
            'orderId' => $result->orderId,
            'formUrl' => $result->formUrl
        ];
    }

    /**
     * @param array $params
     * @return bool
     */
    public static function afterPaid(array $params = [])
    {
        $instance = self::getInstance();
        $orderId = \Yii::$app->request->get('orderId');

        if (!$orderId){
            return false;
        }


        $handler = new SberbankPaymentHandler($instance->params['url'], $instance->params['login'], $instance->params['password']);
        $result = $handler->getOrderStatus($orderId);

        if ($result->ErrorCode == self::ERROR_CODE_SUCCESS && $result->OrderStatus == self::ORDER_STATUS_SUCCESS) {
            $cheque = new ChequeHelper();
            $cheque->printOrder($instance->order);
            return true;
        }
        return false;
    }
}