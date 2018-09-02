<?php

namespace app\modules\backoffice\controllers;

use yii\web\Controller;
use \app\modules\backoffice\models\Lexema;

class DefaultController extends Controller
{

    public function actionIndex()
    {


        $lexema = new Lexema();
        $lexema->test();
    }
}