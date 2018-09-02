<?php

namespace app\widgets\ProductCatWidget;

use app\models\db\ProductCategory;
use yii\base\Widget;
use app\models\db\Setting;
use yii\db\Query;
use yii\db\Expression;

/**
 * Виджет списка категорий
 * @property integer $type
 * @property array $viewType
 */
class ProductCatWidget extends Widget
{
    const TYPE_LIST = 0;
    const TYPE_BLOCKS = 1;
    const TYPE_DROPDOWN = 2;

    public $type = self::TYPE_LIST;

    /** @var array */
    public $viewType = [
        self::TYPE_LIST => 'list',
        self::TYPE_BLOCKS => 'block',
        self::TYPE_DROPDOWN => 'list'
    ];

    /** @var array */
    public $sidebarOptions = [];
    public $sidebarListOptions = [];
    public $sidebarItemOptions = [];

    public function init()
    {
        $this->sidebarOptions['class'] = $this->sidebarOptions['class'] ?? "sidebar";

        if ($this->type == self::TYPE_DROPDOWN) {
            $this->sidebarOptions['class'] .= " sidebar_dropdown";
        } else {
            $this->sidebarOptions['class'] .= "  sidebar_left";
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {

        //Временное кеширование меню. Жрет сцуко 170 запросов к БД). Павел К. 04.04.2018  15:55

        $key = self::class . "run" . $this->type . implode("", $this->sidebarOptions);
        $cache = \Yii::$app->cache;
        if (Setting::get('SITE.CACHE.ENABLE')) {
            $data = $cache->get($key);
        } else {
            $data = false;
        }
        $data = false;
        if ($data === false) {
            // Переделал под один sql-запрос. Алексей 18.04.2018
            $categories = \Yii::$app->registry->getSqlCategories();
            $data = $this->render($this->viewType[$this->type],
                [
                    'sidebarOptions' => $this->sidebarOptions,
                    'categories' => $categories
                ]);
            $cache->set($key, $data, Setting::get('SITE.CACHE.DURATION') * 60); //На 2 часа
        }

        return $data;
    }
}