<?php

namespace app\models\base\product;

use Yii;
use yii\base\BaseObject;
use app\models\db\Product;
use app\models\db\Setting;
use app\models\db\ProductPrice as ProductPriceDB;
use yii\db\ActiveQuery;
use app\models\db\ProductPriceGroup;
use app\components\ClientComponent;
use app\models\db\ProductPriceGroupToClient;
use app\models\db\Client;

/**
 * Class ProductPrice
 *
 * Дополнительный класс помощник для класса Product,
 * который отвечает за цены
 *
 * @package app\models\base\product
 *
 * @property ProductPriceDB[] $all
 * @property ProductPriceDB $default
 * @property  ProductPriceDB $individual
 * @property ProductPriceDB $entity
 * @property ProductPriceDB $client
 * @property ProductPriceDB $priority
 * @property ProductPriceDB $discount
 * @property \stdClass $empty
 * @property int $value
 * @property mixed $val
 */
class ProductPrice extends BaseObject
{
    /** @var Product */
    private $_product;

    /**
     * Список стандартных атрибутов таблицы цены
     * @var array
     */
    public static $attributes = [
        'id',
        'productId',
        'productPriceGroupId',
        'value',
        'val',
        'dtStart',
        'dtEnd',
        'popId'
    ];

    /**
     * Установка продукта
     * @param Product $product
     */
    public function setProduct(Product &$product)
    {
        $this->_product = $product;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (Yii::$app instanceof Yii\console\Application) {
            return null;
        }

        if (in_array($name, self::$attributes)) {
            $price = $this->prepareOut();
            return $price->$name;
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        parent::__set($name, $value);
    }

    /**
     * Получение цены
     */
    public function prepareOut()
    {
        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;

        if ($clientComponent->isIndividual()) {
            return $this->individual ?? $this->empty;
        } elseif ($clientComponent->isEntity()) {
            return $this->entity ?? $this->individual ?? $this->empty;
        }

        return $this->empty;
    }

    /**
     * Устанавливает стандартную цену
     * Если установлен клиент, то ставим цену для клиента,
     * если массив клиентов, то устанавливаем цену им,
     * если bool, то просто добавляет клиентскую цену
     * @param boolean $rewrite
     * @return void
     */
    public function prepareIn(bool $rewrite = false)
    {
        //todo позже этот момент переделать
        if (Yii::$app instanceof Yii\console\Application) {
            return;
        }

        $defaultPriceId = Setting::get('DEFAULT.PRICE.ID'); //Розничный прайс

        if (is_bool($this->_product->_client) && $this->_product->_client === true) {
            $defaultPriceId = Setting::get('WHOLESALE.PRICE.ID'); //Оптовый прайс
        }

        if ($this->_product->_client instanceof Client) {
            $productPriceGroup = ProductPriceGroup::findByClient($this->_client);
            $defaultPriceId = $productPriceGroup->id ?? $defaultPriceId;
        }

        if ($rewrite) {
            ProductPriceDB::deleteAll([
                'productId' => $this->id,
                'productPriceGroupId' => $defaultPriceId
            ]);
        }

        if (!($model = ProductPriceDB::findOne([
            'productId' => $this->_product->id,
            'productPriceGroupId' => $defaultPriceId
        ]))
        ) {
            $model = new ProductPriceDB();
        }

        $model->productId = $this->_product->id;
        $model->productPriceGroupId = $defaultPriceId;
        $model->value = $this->_product->_price ?? $this->_product->defaultPrice;
        $model->save();
    }

    /**
     * Объект, если цена отсутствует
     */
    public function getEmpty()
    {
        $price = new \stdClass();

        foreach (self::$attributes as $attr) {
            $price->$attr = null;
        }

        $price->val = "-";

        return $price;
    }

    /**
     * Возвращает все группы цен / прайсы
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAll()
    {
        return ProductPriceDB::find()
            ->joinWith('productPriceGroup')
            ->joinWith('productPriceGroup.productPriceGroupToClient')
            ->where(['productId' => $this->_product->id])
            ->andWhere('popId is NULL')
            ->orderBy('product_price_group.priority ASC')
            ->asArray()
            ->all();
//            ->createCommand()
//            ->rawSql;
    }

    /**
     * Возвращает стандартную цену
     *
     * @return ProductPriceDB
     */
    public function getDefault()
    {
        $defaultPriceId = Setting::get('DEFAULT.PRICE.ID');

        $price = ProductPriceDB::findOne([
            'productId' => $this->_product->id,
            'productPriceGroupId' => $defaultPriceId,
        ]);

        return $price ?? null;
    }

    /**
     * Устанавливает прайс по умолчанию
     * @param int $price
     */
    public function setDefault(int $price)
    {
        $defaultPrice = $this->default;

        if ($defaultPrice && $defaultPrice->value != $price) {
            $defaultPrice->value = $price;
            $defaultPrice->save();
        }
    }

    /**
     * Цена для физического лица
     * @return ProductPriceDB|\stdClass
     */
    public function getIndividual()
    {
        //todo специальная цена временно
        $priceResult = ProductPriceDB::find()
            ->joinWith('productPriceGroup')
            ->joinWith('productPriceGroup.productPriceGroupToClient')
            ->where(['productId' => $this->_product->id])
            ->andWhere(['product_price_group.clientType' => Client::TYPE_INDIVIDUAL])
            ->andWhere(['product_price_group.priority' => ProductPriceGroup::PRIORITY_DEFAULT_SPECIAL])
            ->one();

        if (!$priceResult) {
            $priceResult = $this->default;
        }

        if (!$priceResult || is_null($priceResult->val)) {
            return $this->empty;
        } else {
            return $this->default;
        }
    }

    /**
     * Цена для юридического лица / клиента
     *
     * @return ProductPriceDB|array|null|\yii\db\ActiveRecord|\stdClass
     */
    public function getEntity()
    {
        $wholesale = Setting::get('WHOLESALE.PRICE.ID');

        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;
        $clientId = $clientComponent->client->id;


        //todo перенсти куда-нибудь
        $productPriceGroupClient = ProductPriceGroupToClient::findOne(['clientId' => $clientId]);
        if ($productPriceGroupClient) {
            $productHasPrice = ProductPriceDB::findOne([
                'productPriceGroupId' => $productPriceGroupClient->productPriceGroupId,
                'productId' => $this->_product->id
            ]);
        } else {
            $productHasPrice = false;
        }

        $price = ProductPriceDB::find()
            ->joinWith('productPriceGroup')
            ->joinWith('productPriceGroup.productPriceGroupToClient')
            ->where(['productId' => $this->_product->id]);

        //todo
        //Обдумать этот момент
        //Будет постоянная привязка прайса к клиенту
//        $productHasPrice = false;

        if ($productPriceGroupClient && $productHasPrice) {
            $price->andWhere([
                'product_price_group_to_client.clientId' => $clientId,
                'product_price_group.priority' => ProductPriceGroup::PRIORITY_DEFINED,
                'product_price_group.clientType' => Client::TYPE_ENTITY
            ]);
        } else {
            $price->andWhere([
                'product_price_group.clientType' => Client::TYPE_ENTITY
            ]);
        }
        $priceResult = $price->one();


        if (!$priceResult || is_null($priceResult->val)) {
            return null;
        } else {
            return $price->one();
        }
    }

    /**
     * Самая приоритетная цена
     *
     * @return ProductPriceDB|array|null|\yii\db\ActiveRecord
     */
    public function getPriority()
    {
        $price = ProductPriceDB::find()
            ->joinWith('productPriceGroup')
            ->where(['productId' => $this->_product->id])
            ->andWhere('priority IS NOT NULL')
            ->orderBy('product_price_group.priority DESC')
            ->one();

        return $price;
    }

    /**
     * Скидочная цена
     *
     * @return ProductPriceDB|array|null|\yii\db\ActiveRecord
     */
    public function getDiscount()
    {
        $price = ProductPriceDB::find()
            ->joinWith('productPriceGroup')
            ->where(['productId' => $this->_product->id])
            ->andWhere(['product_price_group.priority' => ProductPriceGroup::PRIORITY_DISCOUNT])
            ->one();

        return $price;
    }
}