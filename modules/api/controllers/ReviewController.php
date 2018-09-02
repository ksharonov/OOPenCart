<?php

namespace app\modules\api\controllers;

use app\helpers\MailHelper;
use Yii;
use app\models\db\ProductReview;
use app\models\db\User;
use app\system\base\ApiController;
use yii\helpers\Url;
use alexeevdv\recaptcha\RecaptchaValidator;

/**
 * Class ReviewController
 * //todo ограничения на добавления, если у юзера уже есть отзыв?
 * @package app\modules\api\controllers
 */
class ReviewController extends ApiController
{
    /**
     * Отзывы продукта
     */
    public function actionIndex()
    {
//        $validator = new RecaptchaValidator();
//        $isValid = $validator->validateValue(Yii::$app->request->get('recaptcha'));

        if (\Yii::$app->request->isPost) {
            /** @var User $userModel */
            $userModel = \Yii::$app->user->identity;
            $userId = $userModel->id ?? null;

            $review = \Yii::$app->request->post();
            $reviewModel = new ProductReview();
            $reviewModel->load(['ProductReview' => $review]);
            $reviewModel->userId = $userId;
            $reviewModel->status = ProductReview::STATUS_ACTIVE;
            $reviewModel->save();
            MailHelper::newProductReview($reviewModel);
        }
    }
}