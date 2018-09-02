<?php

namespace app\modules\catalog\widgets\FastFilterWidget;

use app\models\db\ProductFilterFast;
use Yii;
//use yii\base\Widget;
use app\system\base\Widget;
use app\modules\catalog\widgets\FastFilterWidget\FastFilterAsset;
use yii\helpers\Json;

class FastFilterWidget extends Widget
{

    public $searchModelName = 'ProductSearch';

    private $_request = [];

    private $_fastFilters = [];

    /**
     * Инициализация виджета
     *
     * @return void
     */
    public function init()
    {
        $this->getData();
    }

    /**
     * Получение основных данных для генерации фильтров
     *
     * @return void
     */
    public function getData()
    {
        $this->_request = Yii::$app->request->get($this->searchModelName) ?? null;

        $categoryId = $this->_request['category'] ?? null;
        if ($categoryId) {
            $fastFilters = ProductFilterFast::find()
                ->where(['categoryId' => $categoryId])
                ->all();
            $link = [];
            foreach ($fastFilters as $fastFilter) {
                $link[$fastFilter->title] = [];
                $link[$fastFilter->title][] = "category={$categoryId}";

                foreach ($fastFilter->attrs as $attr) {
                    $link[$fastFilter->title][] = "{$attr->attr->name}={$attr->attrValue}";
                }

                $defaultFilters = Json::decode($fastFilter->params) ?? [];

                foreach ($defaultFilters as $key => $defaultFilter) {
                    foreach ($defaultFilter as $item) {
                        if ($item != '')
                            if (is_array($item)) {
                                $link[$fastFilter->title][] = "{$key}=" . Json::encode(array_values($item));
                            } else {
                                $link[$fastFilter->title][] = "{$key}={$item}";
                            }
                    }
                }

                $link[$fastFilter->title] = implode("&", $link[$fastFilter->title]);
            }

            $this->_fastFilters = $link;
        }
    }

    public function run()
    {
        $view = $this->getView();
        FastFilterAsset::register($view);

        return $this->render('index', [
            'fastFilters' => $this->_fastFilters
        ]);
    }

}