<?php

namespace app\controllers;

use app\helpers\StringHelper;
use app\system\db\ActiveRecord;
use Yii;
use app\system\base\Controller;
use app\models\search\ProductSearch;
use app\system\template\TemplateStore;

/**
 * Class SearchController
 *
 * @package app\controllers
 */
class SearchController extends Controller
{
    /**
     * Основная страница поиска
     *
     * @return string
     */
    public function actionIndex()
    {
        TemplateStore::setVar("CONTAINER.LAYOUT.SITE.CLASS", 'container');

        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//            'searchModel' => $searchModel
        ]);
    }
}