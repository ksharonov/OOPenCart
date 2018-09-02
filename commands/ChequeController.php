<?php

namespace app\commands;

use app\helpers\ChequeHelper;
use app\models\db\Order;
use yii\console\Controller;

class ChequeController extends Controller
{
    /**
     * Печать чека
     */
    public function actionPrint()
    {
        $cheque = new ChequeHelper();
//        $cheque->printString('11', '111');
//        $cheque->request('openTurn');

        $order = Order::findOne(['id' => 1]);
        $cheque->printOrder($order);
    }

    /**
     * Открытие смены
     * @return void
     */
    public function actionOpen()
    {
        $cheque = new ChequeHelper();
        $cheque->request('OpenTurn');
    }

    /**
     * Закрытие смены
     * @return void
     */
    public function actionClose()
    {
        $cheque = new ChequeHelper();
        $cheque->request('CloseTurn');
    }

    /**
     * Переоткрыть смену
     * @return void
     */
    public function actionUpdateTurn()
    {
        $cheque = new ChequeHelper();
        $cheque->request('CloseTurn');
        $cheque->request('OpenTurn');
    }
}