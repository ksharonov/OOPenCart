<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 26-12-2017
 * Time: 14:26 PM
 */

namespace app\widgets\BreadcrumbsWidget;

use app\models\db\Manufacturer;
use app\models\db\Post;
use app\models\db\Product;
use app\models\db\Page;
use app\models\db\ProductCategory;
use app\models\db\Setting;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Виджет для хлебных крошек
 *
 * @property ActiveRecord | Product | Post | Page $model
 */
class BreadcrumbsWidget extends Widget
{
    const TYPE_CATEGORY = 0;

    public $crumbs = [];

    public $model = null;

    public $type; //category, etc

    public function run()
    {

        if ($this->model instanceof Product) {
            $this->getCrumbsProduct();
        }
        if ($this->model instanceof ProductCategory) {
            $this->getCrumbsProductCategory();
        } elseif ($this->model instanceof Post) {
            $this->getCrumbsPost();
        } elseif ($this->model instanceof Page) {
            $this->getCrumbsPage();
        } elseif ($this->model instanceof Manufacturer) {
            $this->getCrumbsManufacturer();
        }

        if (isset($this->crumbs[0]) && is_array($this->crumbs[0])) {
            foreach ($this->crumbs as &$crumb) {
                $crumb = (object)$crumb;
            }
        }

        return $this->render('index', [
            'crumbs' => $this->crumbs
        ]);
    }

    /**
     * Получение хлебных крошек для продукта
     */
    public function getCrumbsProduct()
    {
        $this->crumbs[] = (object)[
            'title' => 'Главная',
            'link' => Url::home()
        ];

        $ignoredCategoryId = [
            Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID'),
            Setting::get('PRODUCT.LIST.MAIN.CATEGORY.ID'),
            Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID'),
            Setting::get('PRODUCT.PRODUCER.CATEGORY.ID')
        ];

        /*
        foreach ($this->model->categories as $index => $category) {
            if ($category && $category->parentId && !in_array($category->parentId, $ignoredCategoryId)) {
                $this->crumbs[] = (object)[
                    'title' => $category->parent->title ?? null,
                    'link' => $category->parent->link ?? null
                ];
                break;
            }
        }

        foreach ($this->model->categories as $index => $category) {
            if ($category->parentId && !in_array($category->parentId, $ignoredCategoryId)) {
                if ($category && $category->title && $category->title != 'На главной') {
                    $this->crumbs[] = (object)[
                        'title' => $category->title ?? null,
                        'link' => $category->link ?? null
                    ];
                    break;
                }
            }
        }
        */

        // переделал два цикла в один 06.06.18 Алексей
        foreach ($this->model->categories as $index => $category) {
        	if ($category && $category->isDefault) {
        		continue;
			}

			if ($category && $category->parentId) {
				$this->crumbs[] = (object)[
					'title' => $category->parent->title ?? null,
					'link' => $category->parent->link ?? null
					];
			}
			$this->crumbs[] = (object)[
				'title' => $category->title ?? null,
				'link' => $category->link ?? null,
			];
		}

        $this->crumbs[] = (object)[
            'title' => $this->model->title ?? null,
            'link' => $this->model->link ?? null
        ];
    }

    /**
     * Хлебные крошки для категории
     */
    public function getCrumbsProductCategory()
    {
        $this->crumbs[] = (object)[
            'title' => 'Главная',
            'link' => Url::home()
        ];

        if ($this->model->parentId) {
            if ($this->model->parent && $this->model->parent->hasChild()) {
                $this->crumbs[] = (object)[
                    'title' => $this->model->parent->title ?? null,
                    'link' => $this->model->parent->categoryLink ?? null
                ];
            } else {
                $this->crumbs[] = (object)[
                    'title' => $this->model->parent->title ?? null,
                    'link' => $this->model->parent->link ?? null
                ];
            }

        }

        $this->crumbs[] = (object)[
            'title' => $this->model->title ?? null,
            'link' => $this->model->link ?? null
        ];
    }

    /**
     * Хлебные крошки для поста
     */
    public function getCrumbsPost()
    {
        $this->crumbs[] = (object)[
            'title' => 'Главная',
            'link' => Url::home()
        ];

        switch ($this->model->type) {
            case $this->model::TYPE_NEWS: {
                $this->crumbs[] = (object)[
                    'title' => 'Новости',
                    'link' => Url::to(['site/news'])
                ];
                break;
            }
            case $this->model::TYPE_REVIEWS: {
                $this->crumbs[] = (object)[
                    'title' => 'Обзоры',
                    'link' => Url::to(['site/reviews'])
                ];
                break;
            }
        }


        $this->crumbs[] = (object)[
            'title' => $this->model->title ?? null,
            'link' => $this->model->link ?? null
        ];
    }

    /*
    * Хлебные крошки для страницы
    */
    public function getCrumbsPage()
    {
        /* @var Page $this ->model */

        $this->crumbs[] = (object)[
            'title' => 'Главная',
            'link' => Url::home()
        ];

        $this->crumbs[] = (object)[
            'title' => $this->model->title,
            'link' => Url::to($this->model->slug),
        ];
    }


    /**
     * Хлебные крошки для производителя (бренда)
     */
    public function getCrumbsManufacturer()
    {
        $this->crumbs[] = (object)[
            'title' => 'Главная',
            'link' => Url::home()
        ];

        $this->crumbs[] = (object)[
            'title' => 'Производители',
            'link' => Url::to('/brands')
        ];

        if (!$this->model->isNewRecord) {
            $this->crumbs[] = (object)[
                'title' => $this->model->title ?? null,
                'link' => $this->model->link ?? null
            ];
        }
    }

}