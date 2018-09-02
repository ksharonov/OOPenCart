<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 11-12-2017
 * Time: 9:14 AM
 */

namespace app\modules\admin\modules\api\controllers;

use yii\web\Controller;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use yii\filters\VerbFilter;

class ProductUnitsController extends Controller
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
                    'set' => ['POST'],
                    'delete' => ['POST']
                ],
            ],
        ];
    }

    /**
     * Устанавливает ЕИ для продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->post();

        if (!($model = ProductUnit::findOne([
            'productId' => $params['productId'],
            'unitId' => $params['unitId']
        ]))
        ) {
            $model = new ProductUnit();
        }
        $model->productId = $params['productId'];
        $model->unitId = $params['unitId'];
        $model->rate = $params['rate'] ?? 1;
        $model->save();
    }

    /**
     * Удаляет ЕИ продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionDelete()
    {
        $params = \Yii::$app->request->post();

        return ProductUnit::deleteAll(['id' => $params['id']]);
    }
}