<?php

namespace app\modules\admin\controllers;


use app\models\db\Setting;
use app\modules\admin\base\AdminController;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DeliveryCostController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = Setting::findOne(['setKey' => 'DELIVERY.COST.FREE']);

        if (\Yii::$app->request->isPost) {
            $value = \Yii::$app->request->post('Setting')['setValue'];
            $model->setValue = Json::encode($value);
            $model->save();
        }
        $model->setValue = Json::decode($model->setValue);


        $cities = [];

        $preCity = \app\models\db\CityOnSite::find()
            ->groupBy('cityId')
            ->all();

        foreach ($preCity as $item) {
            $cities[$item->city->id] = $item->city->title;
        }

        return $this->render('index', [
            'model' => $model,
            'cities' => $cities
        ]);
    }
}