<?php

namespace app\modules\profile\controllers;

use yii\filters\AccessControl;
use app\system\base\Controller;

class IndexController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user', 'admin', 'manager']
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
