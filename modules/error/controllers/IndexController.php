<?php

namespace app\modules\error\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Error` module
 */
class IndexController extends \app\system\base\Controller
{
    public function actionIndex()
    {

    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function action404()
    {
        return $this->render('404');
    }
}
