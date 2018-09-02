<?php

namespace app\modules\admin\modules\api\controllers;

use yii\web\Controller;
use app\models\db\ProductToCategory;
use yii\filters\VerbFilter;

/**
 * Default controller for the `Module` module
 */
class ProductCategoriesController extends Controller
{

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
     * Устанавливает категорию для продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->post();

        $modelClass = "app\\models\\db\\" . $params['relationModel'];

        if (!($model = $modelClass::findOne([
            $params['relationIdName'] => $params['relationId'],
            'categoryId' => $params['categoryId']
        ]))
        ) {
            $model = new $modelClass();
            $relName = (string)$params['relationIdName'];
            $model->$relName = $params['relationId'];
            $model->categoryId = $params['categoryId'];
        }
        return $model->save();
    }

    /**
     * Удаляет категорию у продукта
     * @return void
     * @throws \yii\web\HttpException
     */
    public function actionDelete()
    {
        $params = \Yii::$app->request->post();

        $modelClass = "app\\models\\db\\" . $params['relationModel'];

        $modelClass::deleteAll([
            $params['relationIdName'] => $params['relationId'],
            'categoryId' => $params['categoryId']
        ]);
    }
}
