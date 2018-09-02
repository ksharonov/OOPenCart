<?php

namespace app\modules\admin\controllers;


use app\models\db\Setting;
use app\modules\admin\base\AdminController;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SliderController extends AdminController
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
        $model = Setting::findOne(['setKey' => 'SITE.SLIDER.IMAGES']);

        if (\Yii::$app->request->isPost) {
            $value = \Yii::$app->request->post('Setting')['setValue'];
            $model->setValue = Json::encode($value);
            $model->save();
        }
        $model->setValue = Json::decode($model->setValue);

        return $this->render('index', [
            'model' => $model
        ]);
    }
}