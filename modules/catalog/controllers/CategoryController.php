<?php

namespace app\modules\catalog\controllers;

use app\models\db\ProductCategory;
use app\system\base\Controller;

/**
 * Class CategoryController
 * @package app\modules\catalog\controllers
 */
class CategoryController extends Controller
{
    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionIndex($id = null)
    {
        if (!$id) {
            return $this->redirect('/');
        }

        // Переделал под новый sql-запрос. Алексей 18.04.2018
        /** @var ProductCategory[] $categories */
        $categories = \Yii::$app->registry->sqlCategories;
        $category = $categories[$id];

        if (!$category) {
            return $this->redirect('/');
        }

        if (!$category->hasChild()) {
            return $this->redirect(['/catalog/index', 'ProductSearch[category]' => $category->id]);
        }

        return $this->render('index', [
            'categories' => $categories,
            'category' => $category
        ]);
    }
}