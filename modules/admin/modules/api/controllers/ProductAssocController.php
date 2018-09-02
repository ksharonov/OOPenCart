<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 11-12-2017
 * Time: 9:14 AM
 */

namespace app\modules\admin\modules\api\controllers;

use yii\web\Controller;
use app\models\db\ProductAssociated as ProductAssoc;
use app\models\db\Unit;
use yii\filters\VerbFilter;

class ProductAssocController extends Controller
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
     * Устанавливает сопутств. продукт
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->post();

        if (!($model = ProductAssoc::findOne([
            'productId' => $params['productId'],
            'productAssociatedId' => $params['productAssociatedId']
        ]))
        ) {
            $model = new ProductAssoc();
        }
        $model->productId = $params['productId'];
        $model->productAssociatedId = $params['productAssociatedId'];
        $model->save();
    }

    /**
     * Удаляет сопутств. продукт
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionDelete()
    {
        $params = \Yii::$app->request->post();

        return ProductAssoc::deleteAll(['id' => $params['id']]);
    }
}