<?php

namespace app\modules\admin\widgets\common\SeoEditWidget;

use app\models\db\Page;
use app\models\db\Post;
use app\models\db\Seo;
use yii\base\Widget;
use yii\db\ActiveRecord;
use app\models\db\Product;

/**
 * Seo-виджет для модели продукта, поста или страницы
 *
 * @property ActiveRecord | Product | Post | Page $model
 */
class SeoEditWidget extends Widget
{
    public $model = null;


    public function run()
    {
        $view = $this->view;
        SeoEditAsset::register($view);

        $seoModel = $this->model->seo ?? new Seo();

        return $this->render('index', [
            'model' => $this->model,
            'seoModel' => $seoModel
        ]);
    }
}