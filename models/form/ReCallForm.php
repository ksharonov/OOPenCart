<?php

namespace app\models\form;

use app\models\db\Setting;
use Yii;
use app\system\base\Model;

/**
 * Class ReCallForm
 *
 * Форма обратного звонка
 *
 * @package app\models\form
 */
class ReCallForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $city;
    public $comment;
    public $check;
    public $verifyCode;
    public $reCaptcha;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'city', 'comment', 'email', 'check'], 'string'],
            [['name', 'phone', 'email'], 'required'],
            //['verifyCode', 'captcha'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'uncheckedMessage' => 'Пожалуйста, подтвердите, что вы не бот']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'city' => 'Город',
            'comment' => 'Комментарий',
            'verifyCode' => '',
            'reCaptcha'=>'',
        ];
    }

    /**
     * Отправить сообщение
     */
    public function save()
    {
        $email = '';
        $body = "{$this->name} просит перезвонить ему на номер {$this->phone}";
        $subject = "Обратный звонок: {$this->phone}";
        return;//todo убрать
        if ($this->validate() && $this->check) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($subject)
                ->setTextBody($body)
                ->send();

        }
    }
}