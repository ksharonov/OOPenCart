<?php

namespace app\modules\product\controllers;

use app\models\db\Product;
use app\system\base\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `product` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($slug = null)
    {
        if ($slug) {
            $product = Product::findOne(['slug' => $slug]);
        } else {
            $product = null;
        }


        if ($product) {
            return $this->render('index', ['product' => $product]);
        } else {
            throw new NotFoundHttpException("Продукт не найден");
        }
    }
}
