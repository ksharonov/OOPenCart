<?php

namespace app\modules\catalog\controllers;

use app\models\db\ProductCategory;
use Yii;
use app\models\db\Product;
use app\system\base\Controller;
use app\system\template\TemplateStore;

/**
 * Class IndexController
 *
 * Контроллер каталога
 *
 * @package app\modules\catalog\controllers
 */
class IndexController extends Controller
{

    public $searchModelName = 'ProductSearch';

    private $_request = [];

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->_request = Yii::$app->request->get($this->searchModelName) ?? null;

        if (!$this->_request) {
            return $this->redirect('/');
        }

        /** Нахождение категории */
        if (isset($this->_request['category'])) {
            $categoryId = $this->_request['category'];
            $category = ProductCategory::findOne($categoryId);
            $title = $category->title ?? "";
        } else {
            $title = "";
            $category = null;
        }

        TemplateStore::setVar("PRODUCT.CATEGORY", $title);

        if (!$category) {
            return $this->redirect('/');
        }

        return $this->render('index', [
            'category' => $category
        ]);
    }
}
