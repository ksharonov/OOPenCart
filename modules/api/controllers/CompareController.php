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
use app\models\db\User;

class CompareController extends Controller
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
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $profile = $user->profile;

            $compareData = $params['compare'] ?? [];

            $profile->compareData = Json::encode($compareData);

            return $profile->save();
        }
        return false;
    }
}