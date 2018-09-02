<?php

namespace app\models\base;

use Yii;
use app\models\db\Setting;
use yii\db\Exception;

/**
 * Class Mailer
 *
 * Класс отправки сообщений
 *
 * @package app\models\base
 */
class Mailer
{
    private static $instance;
    private $config;

    private function __construct()
    {
        $this->config = $this->config();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function config()
    {
        $config = [
            'class' => Setting::get("SMTP.CLASS"),
            'host' => Setting::get("SMTP.HOST"),
            'username' => Setting::get("SMTP.LOGIN"),
            'password' => Setting::get("SMTP.PASSWORD"),
            'port' => Setting::get("SMTP.PORT"),
            'encryption' => Setting::get("SMTP.ENCRYPTION"),
            'streamOptions' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ];
        \Yii::$app->mailer->setTransport($config);
        return $config;
    }

    /**
     * Отправка сообщений
     * @param array $params
     * @throws Exception
     * @return bool|null
     */
    public static function send(array $params = [])
    {
        $init = self::getInstance();
        $config = $init->config;

        if (!key_exists('user', $params)) {
            return null;
        } else {
            $user = $params['user'];
        }
        if (!key_exists('title', $params)) {
            $params['title'] = '';
        }
        if (!key_exists('message', $params)) {
            $params['message'] = '';
        }

        if (!key_exists('file', $params)) {
            $params['file'] = false;
        }

        try {

            $res = \Yii::$app->mailer->compose()
                ->setFrom([Setting::get('SMTP.LOGIN') => $config['username']])
                ->setTo([$user->email => $user->username])
                ->setSubject($params['title'])
                ->setHtmlBody($params['message']);

            if ($params['file'] && $params['file']['size'] > 0) {
                $res->attach($params['file']['tmp_name'], ['fileName' => $params['file']['name'], 'contentType' => $params['file']['type']]);
            }

            return $res->send();

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param array $params
     */
    public static function sendToAdmin(array $params = [])
    {
        $init = self::getInstance();
        $config = $init->config;

        $adminMain = Yii::$app->params['adminEmail'];

        if (!key_exists('file', $params)) {
            $params['file'] = false;
        }

        try {
            $mailer = Yii::$app->mailer->compose()
                ->setTo($adminMain)
                ->setFrom([$params['email'] => $params['name']])
                ->setSubject($params['subject']);

            if ($params['file'] && $params['file']['size'] > 0) {
                $mailer->attach($params['file']['tmp_name'], ['fileName' => $params['file']['name'], 'contentType' => $params['file']['type']]);
            }

            $mailer->setTextBody($params['body'])->send();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param MailMessage $mailMessage
     * @return bool
     */
    public static function sendOut(MailMessage $mailMessage)
    {
        $init = self::getInstance();
        $config = $init->config;

        try {
            $res = \Yii::$app->mailer->compose()
                ->setFrom([$mailMessage->fromEmail => $mailMessage->fromName])
                ->setTo([$mailMessage->toEmail => $mailMessage->toName])
                ->setSubject($mailMessage->subject)
                ->setCharset('utf-8')
                ->setHtmlBody($mailMessage->message);

            if ($mailMessage->file && $mailMessage->file['size'] > 0) {
                $res->attach($mailMessage->file['tmp_name'], ['fileName' => $mailMessage->file['name'], 'contentType' => $mailMessage->file['type']]);
            }

            return $res->send();

        } catch (\Exception $e) {
            $result = $e->getMessage();
//            dump($result);
        }

        return false;
    }
}