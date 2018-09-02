<?php

namespace app\widgets\PartnersBlockWidget;

use app\models\db\Manufacturer;
use app\models\db\Setting;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * Карусель списка новостей, страниц и т.п.
 * @property Model[] $model
 */
class PartnersBlockWidget extends Widget
{
    public function run()
    {
        $config = Json::decode(Setting::get('MAIN.BRAND.LIST'));
        $brandList = Manufacturer::findAll($config);

        return $this->render('index', [
            'brands' => $brandList,
        ]);
    }
}