<?php

namespace app\modules\api\controllers;

use app\models\db\Storage;
use app\models\search\StorageSearch;
use app\system\base\ApiController;
use yii\web\Response;

/**
 * Class StoragesController
 *
 * @package app\modules\api\controllers
 */
class StoragesController extends ApiController
{
    /**
     * Возвращает список хранилищ (постранично)
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        if ($request->isGet) {
            $storageSearch = new StorageSearch();
            $dataProvider = $storageSearch->search($request->get());
            return $dataProvider->models;
        }
    }

    public function actionJson()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $stores = Storage::find()->where(['type' => 1])->all();
        $storesJson = [];

        foreach ($stores as $store) {
            /* @var \app\models\db\Storage $store */
            if(isset($store->address)) {
                $storesJson[] = [
                    'title' => $store->title,
                    'address' => $store->address->data
                ];
            }

        }

        return $storesJson;
    }
}