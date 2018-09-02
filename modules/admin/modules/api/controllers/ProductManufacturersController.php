<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 11-12-2017
 * Time: 14:44 PM
 */

namespace app\modules\admin\modules\api\controllers;

use app\models\db\Manufacturer;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;

class ProductManufacturersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-by-name' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Возвращает возможные фильтры по источнику
     * @return string
     */
    public function actionGetByName()
    {
        $params = \Yii::$app->request->get();

        $params['search'] = $params['search'] ?? '';

        $attributes = Manufacturer::find()
            ->select('id, title')
            ->where(['like', 'title', $params['search']])
            ->asArray()
            ->limit(20)
            ->all();

        return Json::encode($attributes);
    }
}