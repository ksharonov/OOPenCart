<?php

namespace app\extensions\delivery\DeliveryPickupExtension;

use app\models\base\Cart;
use app\models\db\Storage;
use \Exception;
use app\models\db\Extension;
use yii\base\Widget;
use app\system\extension\DeliveryExtension;
use app\system\base\Model;
use app\models\db\Setting;

/**
 * Class DeliveryPickupExtension
 * Способ получения товара самовывозом
 *
 * @package app\extensions\delivery\DeliveryPickupExtension
 */
class DeliveryPickupExtension extends DeliveryExtension
{
    /** Префикс */
    const EXTENSION_ATTR = 'deliveryData';

    /** @var string Класс модели */
    public $extensionModelClass = 'app\extensions\delivery\DeliveryPickupExtension\models\WidgetModel';

    /** @var Model */
    public $extensionModel;

    /**
     * @var bool
     * todo временно
     */
    public $freeDelivery = true;

    /**
     * @return string
     */
    public function run()
    {
        $view = $this->getView();
        DeliveryPickupAsset::register($view);
        $fields = $this->fields;

        if ($this->order->deliveryData) {
            $fields = $this->order->deliveryData;
        }

        $storage = Storage::find()
            ->where(['type' => Storage::TYPE_STORAGE])
            ->all();

        if (!$storage) {
            return null;
        }

        $date = $this->getDate();


        return $this->render($this->_view, [
            'id' => $this->id,
            'order' => $this->order,
            'form' => $this->form,
            'storage' => $storage,
            'extensionParams' => $this->extensionParams,
            'date' => $date
        ]);
    }

    /**
     * Получение даты возможного самовывоза
     * @return \DateTime|null
     */
    public function getDate()
    {
        $date = null;
        $moreTime = true;
        $daysReserved = Setting::get('ORDER.DAYS.RESERVED') + 2;
        $cart = \Yii::$app->cart->get();

        $availByCity = [];
        $allProductsId = [];

        foreach ($cart->items as $item) {
            $allProductsId[] = $item->productId;
            foreach ($item->product->balances as $balance) {
                if (!isset($availByCity[$balance->storage->cityId])) {
                    $availByCity[$balance->storage->cityId] = [];
                }
                $availByCity[$balance->storage->cityId][] = $item->productId;
            }
        }

        foreach ($availByCity as $item) {
            if (count($item) == count($allProductsId)) {
                $moreTime = false;
            }
        }

        if ($moreTime) {
            $date = new \DateTime('now');
            $date->add(new \DateInterval("P{$daysReserved}D"));
        } else {
            $date = new \DateTime('now');
            $date->add(new \DateInterval('P3D'));
        }

        return $date;
    }
}