<?php

namespace app\models\db;

use Yii;
use app\models\db\Client;
use yii\db\ActiveQuery;
use app\helpers\ModelRelationHelper;

/**
 * This is the model class for table "product_price_group".
 *
 * Модель таблицы группы цен (прайсов)
 *
 * Модель для таблицы прайсов
 *
 * @property integer $id
 * @property string $title
 * @property integer $priority
 * @property integer $clientType
 */
class ProductPriceGroup extends \app\system\db\ActiveRecord
{

    //todo подумать над перереализацией структуры приоритетов цен
    /**
     * Розничная цена
     */
    const PRIORITY_DEFAULT = 10;
    const PRIORITY_DEFAULT_SPECIAL = 15;

    /**
     * Цена для клиента
     * @deprecated
     */
    const PRIORITY_CLIENT = 20;

    /**
     * Цена для определённого клиента
     */
    const PRIORITY_DEFINED = 20;

    /**
     * Цена для определённого клиента
     * @deprecated Теперь цена для определённого клиента PRIORITY_CLIENT ^
     */
    const PRIORITY_DEFINED_CLIENT = 25;

    /**
     * Скидочная цена
     * todo временно не используются?
     */
    const PRIORITY_DISCOUNT = 30;

    /**
     * Новинки
     * todo временно не используются?
     */
    const PRIORITY_NEW = 40;

    /**
     * Лучшая цена
     * todo временно не используются?
     */
    const PRIORITY_BEST = 50;

    /**
     * Приоритеры прайсов
     * todo спланировать в будущем перенести эти данные в базу
     * @var array
     */
    public static $priorities = [
        self::PRIORITY_DEFAULT => 'Розничная цена',
        self::PRIORITY_DEFINED => 'Цена опредённого клиента',
        self::PRIORITY_CLIENT => 'Цена для клиента',
        self::PRIORITY_DEFINED_CLIENT => 'Цена для определённого клиента',
        self::PRIORITY_DISCOUNT => 'Скидочная цена',
        self::PRIORITY_NEW => 'Новинки',
        self::PRIORITY_BEST => 'Хиты продаж'
    ];

    /** @var Client */
    private $_client;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['isDefault'], 'boolean'],
            [['priority', 'clientType'], 'integer']
        ];
    }

    /**
     * @param \app\models\db\Client $client
     * @return ProductPriceGroup|array|null|\app\system\db\ActiveRecord
     */
    public static function findByClient(Client $client)
    {
        return ProductPriceGroup::find()
            ->joinWith('productPriceGroupToClient')
            ->where(['clientId' => $client->id])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'priority' => 'Проритет'
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->_client) {
            $this->relateToClient();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Получает клиента прайса
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), [
            'id' => 'clientId'
        ])
            ->viaTable('product_price_group_to_client', ['productPriceGroupId' => 'id']);
    }

    /*
     * Получает запись связующей таблицы
     */
    public function getProductPriceGroupToClient()
    {
        return $this->hasOne(ProductPriceGroupToClient::className(), [
            'productPriceGroupId' => 'id'
        ]);
    }

    /**
     * Связываем прайс с клиентом
     */
    public function relateToClient()
    {
        $productPriceGroupToClient = new ProductPriceGroupToClient();
        $productPriceGroupToClient->productPriceGroupId = $this->id;
        $productPriceGroupToClient->clientId = $this->_client->id;
        $productPriceGroupToClient->save();
    }

    /**
     * Устанавливаем клиента
     */
    public function setClient(Client $client)
    {
        $this->_client = $client;
        $this->priority = self::PRIORITY_DEFINED_CLIENT;
    }
}
