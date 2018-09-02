<?php

namespace app\widgets\SeoWidget;

use Yii;
use yii\base\Widget;
use app\models\db\Product;
use app\models\db\Post;
use app\models\db\Page;
use yii\db\ActiveRecord;

/**
 * Виджет для регистрации SEO-атрибутов продукта, поста или страницы
 *
 * @property ActiveRecord | Product | Post | Page $model
 */
class SeoWidget extends Widget
{

    public $model = null;

    public function run()
    {
        $view = $this->getView();
        $seo = $this->model->seo ?? false;

        if ($seo) {
            if ($seo->title && $seo->title != '') {
                $view->title = $seo->title;
            } else {
                $view->title = $this->model->title;
            }

            \Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => $seo->meta_description
            ]);

            \Yii::$app->view->registerMetaTag([
                'name' => 'keywords',
                'content' => $seo->meta_keywords
            ]);
        } else {
            \Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => \app\models\db\Setting::get('SITE.DESCRIPTION')
            ]);
        }
    }
}