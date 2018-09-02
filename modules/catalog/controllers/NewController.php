<?php

namespace app\modules\catalog\controllers;

use app\system\base\Controller;
use app\models\db\Product;

class NewController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}