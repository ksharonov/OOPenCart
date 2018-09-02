<?php

namespace app\modules\product\controllers;

use Yii;
use yii\helpers\Json;
use app\helpers\CompareHelper;
use app\system\base\Controller;

class CompareController extends Controller
{
    public function actionIndex()
    {
        $cookies = Yii::$app->request->cookies;
        $productIds = Json::decode($cookies->getValue('compare')) ?? [];
        $compare = CompareHelper::createCompareData($productIds);
        $preAttrsGroups = CompareHelper::createShortCompareData($compare);

        return $this->render('index', [
            'compare' => $compare,
            'preAttrsGroups' => $preAttrsGroups
        ]);
    }

}
