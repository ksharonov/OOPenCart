<?php

namespace app\models\db;

use app\models\base\Cart;
use app\models\base\order\OrderActions;
use app\models\base\order\OrderPayment;
use app\models\traits\FileTrait;
use app\models\traits\OrderTrait;
use app\modules\lexema\api\db\LexemaApiOrder;
use app\modules\lexema\api\repository\OrderRepository;
use app\system\extension\PaymentExtension;
use Yii;
use app\models\db\Extension;
use yii\db\ActiveQuery;
use app\models\db\Client;
use app\models\db\User;
use app\models\db\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\db\Setting;
use app\models\db\OrderStatusHistory;
use app\helpers\ModelRelationHelper;
use app\models\base\order\OrderDelivery;
use app\system\behaviors\TextJsonColumnsBehavior;

/**
 * This is the model class for table "order".
 *
 * Модель таблицы заказов
 *
 * @property integer $id
 * @property integer $clientId
 * @property integer $userId
 * @property integer $addressId
 * @property string $comment
 * @property integer $source
 * @property string $dtCreate
 * @property string $dtUpdate
 * @property string $dtDelivery
 * @property string $userData
 * @property string $addressData
 * @property string $clientData
 * @property string $paymentData
 * @property integer $orderStatus
 * @property array $data
 * @property Cart $_content
 * @property integer $paymentMethod
 * @property integer $deliveryMethod
 * @property string $deliveryData
 * @property OrderContent[] $content
 * @property Client $client
 * @property string $dt
 * @property string $dtReserve
 * @property User $user
 * @property Address $address
 * @property integer | float $sum
 * @property integer | float $finalSum
 * @property integer | float $vat
 * @property integer | float $sumWVat
 * @property OrderStatus[] $status
 * @property OrderStatus $lastStatus
 * @property PaymentExtension $paymentExtension
 * @property bool $fromRemote
 * @property OrderActions $action
 * @property array $addCosts
 * @property integer $deliveryCode
 * @property array $discountData
 * @property array $cardData
 * @property float $sumDiscount
 * @property SberbankOrder $sberbank
 * @property Cheque $cheque
 * @property OrderDelivery $delivery
 * @property OrderPayment $payment
 * @static string[] $sources
 */
class Order extends \app\system\db\ActiveRecord
{
    use OrderTrait;
    use FileTrait;

    const SOURCE_SITE = 0;
    const SOURCE_1C = 1;
    const SOURCE_LEXEMA = 2;

    const STATUS_ACTIVE = 0;
    const STATUS_DELETED = 1;

    /** @var Cart */
    public $_content;

    /** @var bool */
    public $fromRemote = false;

    public $_deliveryData;
    public $_paymentData;

    public static $statuses = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_DELETED => 'Удалён'
    ];

    public static $sources = [
        self::SOURCE_SITE => 'Заказ с сайта',
        self::SOURCE_1C => 'Заказ из 1С',
        self::SOURCE_LEXEMA => 'Заказ из Лексемы',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->source = $this->source ?? self::SOURCE_SITE;


        /** @var User $user */
        $user = Yii::$app->user->identity;

        if (!$this->clientId && $user && $user->client) {
            $this->clientId = $user->client->id;
        }

        parent::beforeSave($insert);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {

        if (array_key_exists('id', $changedAttributes) && !$changedAttributes['id']) {
            $orderStatus = new OrderStatusHistory();
            $orderStatus->orderId = $this->id;
            $orderStatus->orderStatusId = Setting::get('DEFAULT.ORDER.ID');
            $orderStatus->save();
        }

        if (!$this->fromRemote) {
            if ($this->_content) {
                foreach ($this->_content->items as $cartItem) {
                    $orderContent = new OrderContent();
                    $orderContent->orderId = $this->id;
                    $orderContent->productId = $cartItem->productId;
                    $orderContent->count = $cartItem->count;
                    $orderContent->save();
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
        return true;
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        $lexemaOrder = LexemaOrder::find()
            ->where(['id' => $this->orderId])
            ->one();

        if ($lexemaOrder) {
            $lexemaOrder->delete();
        }

        parent::afterDelete();
    }

    /**
 * @inheritdoc
 */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dtCreate',
                'updatedAtAttribute' => 'dtUpdate',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => TextJsonColumnsBehavior::className(),
                'attributes' => ['userData', 'addressData', 'clientData', 'deliveryData', 'paymentData', 'addCosts', 'discountData', 'cardData']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientId', 'userId', 'addressId', 'source', 'paymentMethod', 'deliveryMethod', 'deliveryCode'], 'integer'],
            [['dtUpdate', 'dtCreate', 'userData', 'deliveryData', 'paymentData', 'params', 'addCosts', 'discountData', 'cardData'], 'safe'],
            [['comment', 'addressData', 'clientData'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clientId' => 'Клиент',
            'userId' => 'Пользователь',
            'addressId' => 'Адрес',
            'comment' => 'Комментарий',
            'content' => 'Содержимое заказа',
            'source' => 'Источник',
            'dtUpdate' => 'Dt Updated',
            'dtCreate' => 'Дата создания',
            'userData' => 'Данные пользователя',
            'addressData' => 'Данные адреса',
            'clientData' => 'Данные клиента',
            'status' => 'Статус',
            'orderStatus' => 'Статус заказа',
            'category' => 'Категория',
            'paymentMethod' => 'Метод оплаты',
            'paymentData' => 'Данные оплаты',
            'deliveryMethod' => 'Метод доставки',
            'deliveryData' => 'Данные доставки',
            'params' => 'Параметры',
            'deliveryCode' => 'Код получения',
            'cardData' => 'Инфа по скидкам'
        ];
    }

    /**
     * Возвращает клиента привязанного к заказу
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'clientId']);
    }

    /**
     * Устанавливает пользователя к заказу
     */
    public function setUser($user)
    {
        if ($user instanceof User) {
            $this->userId = $user->id ?? null;
            if (!$this->userData){
                $this->userData = $user->data ?? null;
            }
        }
    }

    /**
     * Устанавливает пользователя к заказу
     */
    public function setClient($client)
    {
        if ($client instanceof Client) {

        }
    }

    /**
     * Устанавливает адрес к заказу
     */
    public function setAddress($address)
    {
        if ($address instanceof Address) {
            $this->addressId = $address->id;
            $this->addressData = $address->data;
        }
    }

    /**
     * Возвращает пользователя привязанного к заказу
     *
     * @return ActiveQuery|User|\Closure|object
     */
    public function getUser()
    {
        if ($this->userId) {
            return $this->hasOne(User::className(), ['id' => 'userId']);
        } else {
            return (object)$this->userData;
        }
    }

    /**
     * TODO Это гавно временно. В общем лексема юзает верхний вариант, но он немного неверный
     * @return ActiveQuery
     */
    public function getUserRelation()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * Возвращает адрес заказа
     *
     * @return ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'addressId']);
    }

    /**
     * Возвращает массив заказов
     *
     * @return array
     */
    public function getProductsArray()
    {
        $products = [];
        $arrayProd = Json::decode($this->products);
        foreach ($arrayProd as $product) {
            array_push($products, [
                'model' => Product::findOne($product['id']),
                'count' => $product['count']
            ]);
        }

        return $products;
    }

    /**
     * Возвращает массив статусов
     *
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasMany(OrderStatus::className(), ['id' => 'orderStatusId'])
            ->viaTable('order_status_history', ['orderId' => 'id']);
    }

    /**
     * Возвращает последний статус
     *
     * @return ActiveQuery | object
     */
    public function getLastStatus()
    {
        $obj = $this->hasOne(OrderStatus::className(), ['id' => 'orderStatusId'])
                ->viaTable('order_status_history', ['orderId' => 'id'], function (ActiveQuery $query) {
                    $query
                        ->orderBy(['order_status_history.id' => SORT_DESC])
                        ->limit(1);
                }) ?? (object)[
                'id' => null
            ];
        return $obj;
    }

    /**
     * Имеет ли заказ статус в своей истории
     * @param $statusId
     * @return boolean
     */
    public function isHasStatus(int $statusId)
    {
        return (bool)OrderStatusHistory::find()
            ->where([
                'orderId' => $this->id,
            ])
            ->andWhere([
                'orderStatusId' => $statusId
            ])
            ->count();
    }

    /**
     * @param int $status
     */
    public function setOrderStatus($status)
    {
        if (is_int($status)) {
            $orderHistory = new OrderStatusHistory();
            $orderHistory->orderId = $this->id;
            $orderHistory->orderStatusId = (integer)$status;
            $orderHistory->save();
        }
    }

    /**
     * Устанавливает корзину
     *
     * @param Cart $content
     * @return void
     */
    public function setContent(Cart $content)
    {
        $this->_content = $content;
    }

    /**
     * Возвращает содержимое заказа
     *
     * @return ActiveQuery
     */
    public function getContent()
    {
        return $this->hasMany(OrderContent::className(), ['orderId' => 'id']);
    }

    /**
     * Получение типа пользователя у заказа
     * @return int
     */
    public function getClientType()
    {
        $client = $this->user->client;

        if ($client) {
            return $client->type;
        }

        return Client::TYPE_INDIVIDUAL;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getDt()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtCreate);

        if ($date) {
            return $date->format('d.m.Y');
        } else {
            return $this->dtCreate;
        }
    }

    /**
     * Дата финального резервирования
     */
    public function getDtReserve()
    {
        $daysReserved = Setting::get('ORDER.DAYS.RESERVED') - 1;
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->dtCreate)
            ->modify("+ {$daysReserved} days");

        if ($date) {
            return $date->format('d.m.Y');
        } else {
            return $this->dtCreate;
        }
    }

    /**
     * Возвращает ссылку на товар
     *
     * @return string
     */
    public function getLink()
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/profile/order/' . $this->id . '/']);
    }

    /**
     * @return int|mixed
     */
    public function getSum()
    {
        $sum = 0;

        $content = OrderContent::find()
            ->select('priceValue, count')
            ->where(['orderId' => $this->id])
            ->all();

        foreach ($content as $item) {
            $sum += $item->priceValue * $item->count;
        }

        return $sum;
    }

    /**
     * Сумма со всеми скидками и вычетами
     * @return int|mixed
     */
    public function getFinalSum()
    {
        $sum = $this->sum;

        if (is_array($this->discountData)) {
            foreach ($this->discountData as $key => $discountValue) {
                $sum -= $discountValue;
            }
        }

        if (is_array($this->addCosts)) {
            foreach ($this->addCosts as $key => $costValue) {
                $sum += $costValue;
            }
        }

        if (is_array($this->cardData) && isset($this->cardData['value']) && $this->cardData['use']) {
            $sum -= $this->cardData['value'];
        }

        foreach ($this->content as $item) {
            $sum -= $item->discountValue;
        }

        return $sum;
    }

    /**
     * НДС
     * @return float
     */
    public function getVat()
    {
        return 0.13 * $this->sum;
    }

    /**
     * Сумма
     * @return int
     */
    public function getSumWVat()
    {
        return $this->sum - $this->vat;
    }

    /**
     * Сумма скидок
     * todo скорее всего будет переделываться
     * @return float
     */
    public function getSumDiscount()
    {
        $sum = 0;
        foreach ($this->discountData as $value) {
            $sum += $value;
        }
        return $sum;
    }

    /**
     * Действия над заказом
     */
    public function getAction()
    {
        $orderAction = new OrderActions();
        $orderAction->order = $this;
        return $orderAction;
    }

    /**
     * Связь с таблицей заказов в лексеме
     * @return ActiveQuery
     */
    public function getLexemaOrder()
    {
        return $this->hasOne(LexemaOrder::class, ['orderId' => 'id']);
    }

    /**
     * Получить номер заказа в лексеме
     * @return mixed|null|string
     */
    public function getOrderNumber()
    {
        $orderLexema = LexemaOrder::find()
            ->where(['orderId' => $this->id])
            ->one();

        return $orderLexema->orderNumber ?? null;
    }

    /**
     * Получить vcode - лексемовский id
     * @return null
     * @throws \Exception
     */
    public function getVcode()
    {
        $vcode = $this->lexemaOrder->vcode ?? null;

        if (!$vcode) {
            if (!$this->lexemaOrder) {
                // TODO если нет связи lexemaOrder, то как вообще этот заказ создался
                return null;
            }

            $apiOrder = OrderRepository::get()
                ->find(['OrderId' => $this->lexemaOrder->orderNumber])
                ->one();

            if (!$apiOrder) {
                return null;
            }

            $this->lexemaOrder->vcode = $apiOrder['vcode'];
            $this->lexemaOrder->save();

            $vcode = $this->lexemaOrder->vcode;
        }

        /*
         * todo я хз куда оно пытается присвоить, если поля в базе нет, атрибута у модели нет. Что тут происходит? Шаронов К. 04.06.18
         * В params должно содержаться?
         */
//        @$this->vcode = $vcode;

        return $vcode;
    }

    /*
     * Возвращает дату доставки (+10 дней от даты создания
     */
    public function getDtDelivery()
    {
        $date = new \DateTime($this->dtCreate);
        $date->modify('+10 days');

        return $date->format('d.m.Y');

    }

    /**
     * Поиск модели по Лексемовскому номеру заказа
     * из таблицы lexema_order
     * (который генерируем мы)
     * @param $orderId
     * @return Order | null | LexemaApiOrder
     */
    public static function findByOrderId($orderId)
    {
        $lexemaOrder = LexemaOrder::find()
            ->where(['orderNumber' => $orderId])
            ->one();

        if (!$lexemaOrder || !isset($lexemaOrder->order)) {
            return null;
        }

        $dbLexemaOrder = self::find()
            ->where(['id' => $lexemaOrder->orderId])
            ->one();

        return $dbLexemaOrder ?? null;
    }

    /**
     * Поиск модели заказа по Лексемовскому vcode
     * из таблицы lexema_order
     * @param int $vcode
     * @return Order | null | LexemaApiOrder
     */
    public static function findByVcode(int $vcode)
    {
        $lexemaOrder = LexemaOrder::find()
            ->where(['vcode' => $vcode])
            ->one();

        if (!$lexemaOrder || !isset($lexemaOrder->order)) {
            return null;
        }

        $dbLexemaOrder = self::find()
            ->where(['id' => $lexemaOrder->orderId])
            ->one();

        if ($dbLexemaOrder && $dbLexemaOrder->hasProperty('vcode')) {
            $dbLexemaOrder->vcode = $lexemaOrder->vcode;
        }

        return $dbLexemaOrder ?? null;
    }

    /**
     *
     * Получить связанный заказ в таблице сбера
     * todo скорее всего потом надо перенести саму модель и геттер в extension, но пока тут, чтоб быстро
     * @return ActiveQuery
     */
    public function getSberbank()
    {
        return $this->hasOne(SberbankOrder::class, ['orderId' => 'id']);
    }

    /**
     * Чек
     * @return ActiveQuery
     */
    public function getCheque()
    {
        return $this->hasOne(Cheque::class, ['orderId' => 'id'])
            ->where(['path' => 'Complex']);
    }
}
