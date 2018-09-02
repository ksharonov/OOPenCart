<?php

namespace app\modules\catalog\widgets\SortWidget;

//use yii\base\Widget;
use app\system\base\Widget;
use yii\helpers\Url;

class SortWidget extends Widget
{
    const SORT_TITLE = 'title';
    const SORT_PRICE = 'price';

    public static $sort = [
        self::SORT_TITLE => 'Сортировка по названию',
        self::SORT_PRICE => 'Сортировка по цене'
    ];

    public function run()
    {
        $view = $this->getView();
        SortAsset::register($view);
        $currentUrl = Url::current([
            'sort' => "price"
        ]);

        $sortData = [
            [
                'title' => 'По умолчанию',
                'value' => '+id'
            ],
            [
                'title' => 'По наименованию',
                'value' => '+title'
            ],
            [
                'title' => 'По возрастанию цены',
                'value' => '+price'
            ],
            [
                'title' => 'По убыванию цены',
                'value' => '-price'
            ],
            [
                'title' => 'По рейтингу',
                'value' => '+rating'
            ],
        ];

//        foreach (self::$sort as $key => $title) {
//            $sortData[] = (object)[
//                'title' => $title,
//                'sort' => $key
//            ];
//        }

        return $this->render('index', [
            'sortData' => $sortData
        ]);
    }
}