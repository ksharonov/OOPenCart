<?php

namespace app\widgets\MainSliderWidget;

use app\models\db\Setting;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * Карусель списка новостей, страниц и т.п.
 * @property Model[] $model
 */
class MainSliderWidget extends Widget
{
    public $model;

    public function run()
    {
        $sliders = Json::decode(Setting::get('SITE.SLIDER.IMAGES')) ?? [];

        if (is_string($sliders)) {
            $sliders = [];
        }

        $view = $this->getView();
        MainSliderAsset::register($view);

        return $this->render('index', [
            'model' => $this->model,
            'sliders' => $sliders
        ]);
    }
}