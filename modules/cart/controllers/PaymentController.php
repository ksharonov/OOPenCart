<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 12-12-2017
 * Time: 14:08 PM
 */

namespace app\modules\cart\controllers;

use app\system\base\Controller;

class PaymentController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}