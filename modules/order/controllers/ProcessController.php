<?php

namespace app\modules\order\controllers;

use app\components\ClientComponent;
use app\models\base\Cart;
use app\models\base\order\OrderProcess;
use app\models\cookie\CartCookie;
use app\models\db\User;
use app\modules\order\assets\ProcessAsset\ProcessAsset;
use app\modules\order\assets\PrintAsset\PrintAsset;
use Yii;
use app\system\base\Controller;
use app\components\CartComponent;
use app\models\session\OrderSession;
use app\models\db\Extension;
use app\models\db\Client;
use app\models\db\Order;
use yii\db\Exception;

/**
 * Class ProcessController
 *
 * Оформление заказа
 *
 * @package app\modules\order\controllers
 */
class ProcessController extends Controller
{

    /** @var OrderSession */
    public $order;

    /** @var Cart */
    public $cart;

    /** @var OrderProcess */
    public $process;

    /** @var array */
    public static $contactsViews = [
        Client::TYPE_INDIVIDUAL => '_contact_individual',
        Client::TYPE_ENTITY => '_contact_entity'
    ];

    /** @var boolean | integer  режим терминала(вкл/выкл) */
    public $terminal;


    /**
     * Действия перед заходом на сайт
     *
     * @param \yii\base\Action $action
     * @return bool|\yii\web\Response
     */
    public function beforeAction($action)
    {
        if ($action->id == 'delivery') {
            OrderSession::deleteAll();
        }

        $view = $this->getView();
        ProcessAsset::register($view);

        // режим терминала?
        $this->terminal = Yii::$app->setting->get('TERMINAL.STATUS');
        $terminalClient = Yii::$app->setting->get('TERMINAL.CLIENT');

        /** @var CartComponent $cartComponent */
        $cartComponent = Yii::$app->cart;
        $this->cart = $cartComponent->get();

        // изменение
        if ($cartComponent->isNull() && $action->id !== 'final' && $action->id !== 'print') {
            $this->redirect('/');
            return false;
        }

        $params = Yii::$app->request->post();
        $order = null;

        $order = OrderSession::get();

        if (!$order) {
            $order = new OrderSession();
            $order->save();
        }

        if ($params && $order->load($params)) {
            $order->save();
        }

        $this->order = $order;

        $this->process = new OrderProcess();


        return parent::beforeAction($action);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        Yii::$app->session->remove('print_order_id');
        // изменение
        if (!$this->terminal)
            return $this->redirect('delivery');
        else return $this->redirect('payment');
    }

    /**
     * Страница доставки
     *
     * @return string
     * @throws Exception
     */
    public function actionDelivery()
    {
        $deliveries = Extension::find()
            ->where(['type' => Extension::EXTENSION_TYPE_DELIVERY])
            ->andWhere(['status' => Extension::STATUS_ACTIVE]);

        if (\Yii::$app->user->isGuest) {
            $deliveries->andWhere(['access' => Extension::ACCESS_GUEST]);
        } else {
            $deliveries->andWhere([
                'OR',
                ['access' => Extension::ACCESS_AUTH],
                ['access' => Extension::ACCESS_GUEST],
                'access IS NULL',
            ]);
        }

        $deliveries = $deliveries
            ->indexBy('id')
            ->all();

        if (!$deliveries) {
            throw new Exception('В базе отсутствуют расширения доставки');
        }

        $delivery = current($deliveries);

        $this->order->deliveryMethod = $delivery->id;

        $this->order->state = OrderSession::STATE_DELIVERY;

        return $this->render('delivery', [
            'cart' => $this->cart,
            'order' => $this->order,
            'process' => $this->process,
            'deliveries' => $deliveries
        ]);
    }

    /**
     * Страница контактов
     *
     * @return string
     */
    public function actionContacts()
    {
        if (!$this->process->delivery) {
            return $this->redirect('/order/process/delivery');
        }

        $this->order->state = OrderSession::STATE_CONTACTS;

        $this->order->save();

        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;

        if ($clientComponent->isIndividual()) {
            return $this->render('contacts', [
                'cart' => $this->cart,
                'order' => $this->order,
                'process' => $this->process
            ]);
        }

        if ($clientComponent->isEntity()) {
            return $this->render('contacts_entity', [
                'cart' => $this->cart,
                'order' => $this->order,
                'process' => $this->process
            ]);
        }
    }

    /**
     * Страница оплаты
     *
     * @return string|\yii\web\Response
     * @throws Exception
     */
    public function actionPayment()
    {
        // изменение
        if (!$this->process->contacts && !$this->terminal) {
            return $this->redirect('/order/process/contacts');
        }

        $this->order->state = OrderSession::STATE_PAYMENT;

        $payments = Extension::find()
            ->where(['type' => Extension::EXTENSION_TYPE_PAYMENT])
            ->andWhere(['status' => Extension::STATUS_ACTIVE])
            ->andWhere('parentId IS NULL');

        if (\Yii::$app->user->isGuest) {
            $payments->andWhere(['access' => Extension::ACCESS_GUEST]);
        } elseif (Yii::$app->client->isIndividual()) {
            $payments->andWhere([
                'OR',
                ['access' => Extension::ACCESS_AUTH],
                ['access' => Extension::ACCESS_GUEST]
            ]);
        } elseif (Yii::$app->client->isEntity()) {
            $payments->andWhere([
                'OR',
                ['access' => Extension::ACCESS_AUTH],
                ['access' => Extension::ACCESS_GUEST],
                ['access' => Extension::ACCESS_ENTITY]
            ]);
        }


        $payments = $payments->all();
//        dump($orderSession);

        if (!$payments) {
            throw new Exception('В базе отсутствуют расширения способов оплаты');
        }

        $this->order->paymentMethod = $payments[0]->id ?? null;

        $this->order->save();

        return $this->render('payment', [
            'cart' => $this->cart,
            'order' => $this->order,
            'payments' => $payments,
            'process' => $this->process
        ]);
    }

    /**
     * Финальная страница
     *
     * @return string
     */
    public function actionFinal()
    {
        if (Yii::$app->request->post()) {
            //dump(Yii::$app->request->post()); exit;
            return $this->redirect('/order/submit/index');
        }

        $this->order->state = OrderSession::STATE_FINAL;

        $orderSession = OrderSession::get();

        $id = $orderSession->id;

        Yii::$app->session->set('print_order_id', $id);

        if (!isset($orderSession->order)) {
            return $this->redirect('/');
        }

        CartCookie::deleteAll();

        //todo убрать
        OrderSession::deleteAll();

        return $this->render('final', [
            'cart' => $this->cart,
            'order' => $this->order,
            'process' => $this->process
        ]);
    }

    /**
     * @return string
     *
     * Страница-чек для печати
     */
    public function actionPrint()
    {
        PrintAsset::register($this->getView());

        $print_order_id = Yii::$app->session->get('print_order_id');

        if (!isset($print_order_id)) {
            return $this->redirect('/');
        }

        $order = Order::findOne($print_order_id);

        if (!isset($order)) {
            return $this->redirect('/');
        }

        $priceSummary = 0;

        $itemsSummary = count($order->content);

        foreach ($order->content as $item) {
//            $itemsSummary += $item->count;
            $priceSummary += $item->count * $item->priceValue;
        }

        OrderSession::deleteAll();

        return $this->render('print', [
            'order' => $order,
            'itemSummary' => $itemsSummary,
            'priceSummary' => $priceSummary,
        ]);
    }

    public function actionError()
    {
        return $this->render('payment', [
            'cart' => $this->cart,
            'order' => $this->order,
            'process' => $this->process
        ]);
    }
}