<?php

namespace app\system\base;

use Yii;

/**
 * Class ApiController
 *
 * Контроллер для API
 *
 * @package app\system\base
 */
class ApiController extends Controller
{
    public function beforeAction($action)
    {
//        if (!Yii::$app->request->isAjax) {
//            return $this->redirect('/');
//        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }
}