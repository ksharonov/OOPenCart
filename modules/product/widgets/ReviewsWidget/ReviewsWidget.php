<?php

namespace app\modules\product\widgets\ReviewsWidget;

use Yii;
use yii\base\Widget;
use app\models\db\ProductReview;
use yii\db\ActiveRecord;

/**
 * Виджет для добавления отзывов для товаров
 *
 * @property ActiveRecord $model
 */
class ReviewsWidget extends Widget
{

    public $model;

    public function run()
    {
        $view = $this->getView();
        ReviewsAsset::register($view);

        $reviewModel = ProductReview::findOne([
            'productId' => $this->model->id,
            'userId' => Yii::$app->user->identity->id ?? null
        ]);
//
//        if ($res) {
//            $reviewModel->productId = $this->model->id;
//            $reviewModel->userId = Yii::$app->user->identity->id ?? null;
//            $reviewModel->save();
//        }
        return $this->render('index', [
            'model' => $this->model,
            'reviewModel' => $reviewModel
        ]);
    }
}