<?php

namespace app\helpers;

use app\models\base\Mailer;
use app\models\base\MailMessage;
use app\models\base\post\Review;
use app\models\db\Order;
use app\models\db\Post;
use app\models\db\ProductReview;
use app\models\db\Setting;
use app\models\form\ContactForm;
use app\models\form\ReCallForm;
use app\models\form\RestorePasswordForm;

/**
 * Class MailHelper
 * @package app\helpers
 */
class MailHelper
{
    /**
     * Новый запрос в обратную связь
     * @param ContactForm $contactForm
     */
    public static function newFeedback(ContactForm $contactForm)
    {
        $mm = new MailMessage();
        $mm->toName = 'Контент-менеджер';
        $mm->toEmail = Setting::get('MAIL.CONTENT.MANAGER');
        $mm->fromName = $contactForm->name;
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = "Новый запрос об обратной связи: {$contactForm->subject}, {$contactForm->email}";
        $mm->message = $contactForm->body;
        Mailer::sendOut($mm);
    }

    /**
     *
     * Новый заказ
     * @param Order $order
     */
    public static function newOrder(Order $order)
    {
        $emailFrom = $order->user->email ?? null;
        $userData = (object)$order->userData ?? null;

        $mm = new MailMessage();
        $mm->toName = $order->user->username ?? null;
        $mm->toEmail = $userData->email ?? $order->user->email ?? null;
        $mm->fromName = Setting::get('SITE.NAME');
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = "Новый заказ на сайте ". Setting::get('SITE.NAME');
        $mm->message = \Yii::$app->view->render('@app/mail/order/new', [
            'order' => $order
        ]);;
        Mailer::sendOut($mm);


        $mm = new MailMessage();
        $mm->toName = 'Менеджер по продажам';
//        if (\Yii::$app->client()->isEntity()){}
        $mm->toEmail = Setting::get('MAIL.SALES.MANAGER');
        $mm->fromName = $order->user->username ?? null;
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = "Новый заказ на сайте с номером: {$order->id}, {$emailFrom}";
        $mm->message = \Yii::$app->view->render('@app/mail/order/new_manager', [
            'order' => $order
        ]);;
        Mailer::sendOut($mm);
    }

    /**
     * Новый отзыв на сайте
     */
    public static function newReview(Post $review)
    {
        $mm = new MailMessage();
        $mm->toName = 'Контент-менеджер';
        $mm->toEmail = Setting::get('MAIL.CONTENT.MANAGER');
        $mm->fromName = $review->title;
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = "Отзыв от {$review->title}";
        $mm->message = "На сайт добавлен отзыв о компании: {$review->id}";
        Mailer::sendOut($mm);
    }

    /**
     * Новый отзыв на продукт
     * @param ProductReview $productReview
     */
    public static function newProductReview(ProductReview $productReview)
    {
        $emailReview = $productReview->user->email ?? Setting::get('MAIL.CONTENT.MANAGER');

        Setting::get('SMTP.LOGIN');
        $mm = new MailMessage();
        $mm->toName = 'Контент-менеджер';
        $mm->toEmail = Setting::get('MAIL.CONTENT.MANAGER');
        $mm->fromName = 'Пользователь';
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = 'На сайт добавлен новый отзыв о товаре';
        $mm->message = "На сайт добавлен отзыв: {$productReview->id} на продукт {$productReview->product->title}(productId: {$productReview->product->id}), ({$emailReview})";
        //Mailer::sendOut($mm); //Убрал Павел 19_07. СПАМ жесткий
    }

    /**
     * Новый запрос в обратный звонок
     * @param ReCallForm $reCallForm
     */
    public static function newCallback(ReCallForm $reCallForm)
    {
        $mm = new MailMessage();
        $mm->toName = 'Контент-менеджер';
        $mm->toEmail = Setting::get('MAIL.CONTENT.MANAGER');
        $mm->fromName = $reCallForm->name;
        $mm->fromEmail = Setting::get('SMTP.LOGIN');
        $mm->subject = 'Запрос на обратный звонок';
        $mm->message = "Запрос на обратный звонок: {$reCallForm->comment}, email: {$reCallForm->email}, телефон: {$reCallForm->phone}";
        Mailer::sendOut($mm);
    }

    /**
     * Сообщение восстановления пароля
     * @param RestorePasswordForm $restorePasswordForm
     * @param string $url
     */
    public static function restorePassword(RestorePasswordForm $restorePasswordForm, $url = null)
    {
        $message = \Yii::$app->view->render('@app/mail/user/restorePassword', [
            'url' => $url
        ]);

        Mailer::send([
            'title' => 'Восстановление пароля',
            'message' => $message,
            'user' => $restorePasswordForm->user,
        ]);
    }

    public static function test()
    {
        return false;
        $view = \Yii::$app->view->render('@app/mail/order/new', [
            'order' => \app\models\db\Order::findOne(1)
        ]);
        return $view;
    }
}