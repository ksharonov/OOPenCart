<?php

namespace app\modules\api\controllers;

use app\models\db\Country;
use app\system\base\ApiController;

/**
 * Class CountryController
 * @package app\modules\api\controllers
 */
class CountryController extends ApiController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isGet) {
            $params = \Yii::$app->request->get();

            $country = Country::find()
                ->select('title')
                ->where(['like', 'title', $params])
                ->column();

            return $country;
        }
    }
}