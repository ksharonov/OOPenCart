<?php

namespace app\modules\catalog\controllers;

use app\models\db\ProductPriceGroup;
use app\system\base\Controller;
use app\models\db\Product;

class DiscountController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}