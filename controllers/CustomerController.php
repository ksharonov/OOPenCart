<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\base\Mailer;
use app\models\db\Post;
use app\system\base\Controller;
use app\system\template\TemplateStore;

/**
 * Class CustomerController
 *
 * Страницы группы "Покупатель"
 *
 * @package app\controllers
 */
class CustomerController extends Controller
{
    public function beforeAction($action)
    {
        TemplateStore::setVar("CONTAINER.LAYOUT.SITE.CLASS", 'container');
        return parent::beforeAction($action);
    }

    /**
     * Страница "Покупателям"
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница "Оплата"
     */
    public function actionPayment()
    {
        return $this->render('payment');
    }

    /**
     * Страница "Доставка"
     */
    public function actionDelivery()
    {
        return $this->render('delivery');
    }

    /**
     * Страница "Отзывы"
     */
    public function actionReviews()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $newReview = new Post();
            $newReview->load(['Post' => $params]);
            $newReview->type = Post::TYPE_REVIEW_COMPANY;

            if (\Yii::$app->user->isGuest) {
                $addTitle = $params['title'] . " " . $params['name'] . " " . $params['email'];
                $newReview->title = $addTitle;
            } else {
                $newReview->userId = \Yii::$app->user->identity->id;
            }

            if ($newReview->validate() && $newReview->save(false)) {
                MailHelper::newReview($newReview);
//                Mailer::sendToAdmin([
//                    'email' => $params['email'],
//                    'subject' => 'На сайт добавлен новый отзыв',
//                    'name' => $params['name'],
//                    'body' => "Отзыв от пользователя {$params['email']}"
//                ]);

                return $this->redirect(' / ');
            }
        }

        $reviews = Post::find()
            ->where(['type' => Post::TYPE_REVIEW_COMPANY])
            ->andWhere(['status' => Post::POST_PUBLISHED])
            ->limit(10)
            ->all();
        return $this->render('reviews', [
            'reviews' => $reviews
        ]);
    }

    /**
     * Страница "Акции и предложения"
     */
    public function actionOffers()
    {
        return $this->render('offers');
    }

    /**
     * Страница "Вопросы и ответы"
     * @return string
     */
    public function actionFaq()
    {
        return $this->render('faq');
    }
}