<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace app\widgets\CitySelectWidget;


use app\models\cookie\CityCookie;
use app\models\db\CityOnSite;
use app\models\db\City;
use app\models\db\UserProfile;
use app\system\base\Widget;
use yii\helpers\ArrayHelper;

class CitySelectWidget extends Widget
{
    /** @var array | City */
    public $cities;

    /** @var UserProfile  */
    public $profile;

    /** @var CityCookie */
    public $model;

    public function init()
    {
        $cityOnSite = CityOnSite::find()->all();
        $cities = [];

        foreach ($cityOnSite as $city) {
            $cities[] = $city->city;
        }

        $this->cities = ArrayHelper::map($cities, 'id', 'title');

        $user = \Yii::$app->user->identity ?? null;

        if ($user && !isset($user->profile)) {
            $this->profile = new UserProfile();
            $this->profile->userId = $user->id;
            $this->profile->save();
        }
        else if ($user && isset($user->profile)) {
            $this->profile = $user->profile;
        }

        $this->model = CityCookie::get();

        if (!$this->model) {
            $this->model = new CityCookie();
            $this->model->save();
        }
    }

    public function run()
    {
        $view = $this->getView();
        CitySelectAsset::register($view);

//        $params = \Yii::$app->request->post('CityCookie');
//        $city = $this->model->citySelected;
//
//        if ($params['citySelected']) {
//            $city = $params['citySelected'];
//        } else if ($this->profile) {
//            $city = $this->profile->citySelected;
//        }
//        $city = $params['citySelected'];
//
//        $this->model->citySelected = $city;
//        $this->model->save();

        return $this->render('index', [
            'cities' => $this->cities,
            'model' => $this->model,
            ]);
    }
}