<?php

namespace app\modules\api\controllers;

use app\models\db\Storage;
use app\system\base\ApiController;
use yii\helpers\Json;
use app\models\db\Setting;

class DeliveryController extends ApiController
{
    public function actionGetDate()
    {
        $params = \Yii::$app->request->get('data') ?? null;
        $storageId = $params['storageId'] ?? null;
        $storage = false;
        $date = null;

        if ((int)$storageId) {
            $storage = Storage::find()
                ->where(['id' => $storageId])
                ->one();
        }

        if ($storage) {
            $date = $this->getDate($storage);
        }

        return $date->format('d.m.Y') ?? null;
    }

    /**
     * Получение даты возможного самовывоза
     * @param $storage
     * @return \DateTime|null
     */
    public function getDate($storage)
    {
        $date = null;
        $moreTime = true;
        $daysReserved = Setting::get('ORDER.DAYS.RESERVED') + 2;
        $cityId = $storage->city->id ?? null;
        $storageId = $storage->id ?? null;
        $cart = \Yii::$app->cart->get();

        $availByStorage = [];
        $availByCity = [];
        $allProductsId = [];

        foreach ($cart->items as $item) {
            $allProductsId[] = $item->productId;
            foreach ($item->product->balances as $balance) {
                if (!isset($availByCity[$balance->storage->cityId])) {
                    $availByCity[$balance->storage->cityId] = [];
                }

                if (!isset($availByStorage[$balance->storage->id])) {
                    $availByStorage[$balance->storage->id] = [];
                }

                if (!in_array($item->productId, $availByCity[$balance->storage->cityId])) {
                    $availByCity[$balance->storage->cityId][] = $item->productId;
                }

                if ($item->count <= $balance->quantity && !in_array($item->productId, $availByStorage[$balance->storage->id])) {
                    $availByStorage[$balance->storage->id][] = $item->productId;
                }

            }
        }
//        var_dump($availByCity[$cityId]);
        if (isset($availByCity[$cityId]) && count($availByCity[$cityId]) == count($allProductsId)) {
            $moreTime = false;
        }

        if ($moreTime) {
            $date = new \DateTime('now');
            $date->add(new \DateInterval("P{$daysReserved}D"));
        } else {
            $date = new \DateTime('now');
            $date->add(new \DateInterval('P3D'));
        }

        if (isset($availByStorage[$storageId]) && count($availByStorage[$storageId]) == count($allProductsId)) {
            $date = new \DateTime('now');
            $date->add(new \DateInterval('P2D'));
        }

        return $date;
    }
}