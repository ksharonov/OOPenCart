<?php

namespace app\modules\admin\modules\api\controllers;

use app\models\db\ProductOptionValue;
use yii\web\Controller;

/*
 * Контроллер для взаимодействия значений опций с опциями
 */
class ProductOptionValuesController extends Controller {

    public function actionUpdateOptionValue()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['optionValueId']) && isset($params['newValue'])) {
                $optionValue = ProductOptionValue::findOne(['id' => $params['optionValueId']]);

                $optionValue->value = $params['newValue'];
                $optionValue->save();
            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }

    public function actionDeleteOptionValue()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['optionValueId'])) {
                $optionValue = ProductOptionValue::findOne(['id' => $params['optionValueId']]);

                $optionValue->delete();
            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }

    public function actionCreateOptionValue()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['newValue']) && isset($params['optionId'])) {
                $optionValue = new ProductOptionValue();
                $optionValue->optionId = $params['optionId'];
                $optionValue->value = $params['newValue'];
                $optionValue->save();

            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }
}