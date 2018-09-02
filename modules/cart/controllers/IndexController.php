<?php

namespace app\modules\cart\controllers;

use Yii;
use app\system\base\Controller;

/**
 * Default controller for the `Module` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
