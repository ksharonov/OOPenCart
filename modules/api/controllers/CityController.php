<?php

namespace app\modules\api\controllers;

use app\models\db\City;
use app\system\base\ApiController;
use app\models\cookie\CityCookie;
use app\models\db\UserProfile;

/**
 * Class CityController
 * @package app\modules\api\controllers
 */
class CityController extends ApiController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isGet) {
            $params = \Yii::$app->request->get();
            $city = City::find()
                ->select('title')
                ->where(['like', 'title', $params])
                ->column();

            return $city;
        }
    }

    public function actionSelectCity()
    {
        $post = \Yii::$app->request->post();
        $user = \Yii::$app->user->identity ?? null;
        $profile = null;

        if ($user && !isset($user->profile)) {
            $profile = new UserProfile();
            $profile->userId = $user->id;
            $profile->save();
        } else if ($user && isset($user->profile)) {
            $profile = $user->profile;
        }

        $model = CityCookie::get();

        if (!$model) {
            $model = new CityCookie();
            $model->citySelected = $post['citySelected'] ?? null;
            $model->save();
        }

        $city = $model->citySelected;

        if ($post['citySelected']) {
            $city = $post['citySelected'];

            if ($profile) {
                $profile->citySelected = $post['citySelected'] === "null" ? null : $post['citySelected'];
                $profile->save();
            }
        }

        $model->citySelected = $city;
        $model->save();

//        dump([
//            $model
//        ]);

        return true;
    }
}