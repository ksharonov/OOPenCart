<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 14-12-2017
 * Time: 14:34 PM
 */

namespace app\modules\api\controllers;

use Yii;
use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FavoriteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'set' => ['POST']
                ],
            ],
        ];
    }

    public function actionSet()
    {
        $params = Yii::$app->request->post();

        if (!Yii::$app->user->isGuest) {
            $profile = Yii::$app->user->identity->profile;
            $profile->favoriteData = Json::encode($params['favorite']);

            return $profile->save(false);
        }
        return false;
    }
}