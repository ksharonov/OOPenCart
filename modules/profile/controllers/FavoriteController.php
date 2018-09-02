<?php

namespace app\modules\profile\controllers;

use app\components\FavoriteComponent;
use Yii;
use yii\helpers\Json;
use app\system\base\Controller;

/**
 * Class FavoriteController
 *
 * Страницы, которые относятся к избранному
 *
 * @package app\modules\profile\controllers
 */
class FavoriteController extends Controller
{

    public function actionIndex()
    {
        /** @var FavoriteComponent $favoriteComponent */
        $favoriteComponent = Yii::$app->favorite;

        $favorite = $favoriteComponent->get();
        list($searchModel, $dataProvider) = $favoriteComponent->searchProvider;

        return $this->render('index', [
            'favorite' => $favorite,
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider
        ]);
    }
}