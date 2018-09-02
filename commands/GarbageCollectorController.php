<?php

namespace app\commands;

use app\models\db\Order;
use app\models\db\ProductUnit;
use app\models\db\Setting;
use yii\console\Controller;

/**
 * Class GarbageCollectorController
 *
 * Сборщик мусора
 *
 * @package app\commands
 */
class GarbageCollectorController extends Controller
{
    /**
     * Запускатор
     * @return void
     */
    public function actionIndex()
    {
        $this->unitCollector();
    }

    /**
     * Очистка лишних единиц измерения
     * @return void
     */
    public function unitCollector()
    {
        $productUnits = ProductUnit::find()
            ->groupBy('productId, unitId')
            ->orderBy('productId')
            ->asArray()
            ->indexBy('id')
            ->column();

        ProductUnit::deleteAll(['NOT IN', 'id', $productUnits]);
    }

    /**
     * Отмена устаревших заказов
     * @return void
     */
    public function actionOrder()
    {
        $orders = Order::find()->all();

        foreach ($orders as $order) {
            $timeDiff = (strtotime($order->dtReserve) - strtotime($order->dtc)) / 60 / 60 / 12;
            if ($timeDiff > Setting::get('ORDER.DAYS.RESERVED')) {
                $order->action->cancel();
            }
        }

    }
}