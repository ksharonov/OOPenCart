<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 21-05-2018
 * Time: 11:13 AM
 */

namespace app\commands;


use app\models\db\Order;
use app\models\db\OrderStatusHistory;
use app\models\db\Setting;
use app\modules\lexema\models\OrderExport;
use yii\console\Controller;

class OrderController extends Controller
{
    /**
     * Отправка неотправленных заказов в Лексему
     */
    public function actionLexemaSend()
    {
        $successOrderStatusId = Setting::get('ORDER.STATUS.SEND');

        $ordersIds = OrderStatusHistory::find()
            ->select('orderId')
            ->groupBy('orderId')
            ->having("GROUP_CONCAT(`orderStatusId`) NOT LIKE '%{$successOrderStatusId}%'");

        $orders = Order::find()
            ->where(['id' => $ordersIds])
            ->all();

        foreach ($orders as $order) {
            $orderExport = new OrderExport();
            $result = $orderExport->sendOrder($order);
        }
    }
}