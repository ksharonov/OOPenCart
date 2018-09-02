<?php

namespace app\models\form;

use app\models\base\Mailer;
use Yii;
use yii\base\Model;
use app\models\db\Setting;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $city;
    public $subject;
    public $body;
//    public $verifyCode;
    public $file;
    public $verifyCode;
    public $reCaptcha;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body', 'phone'], 'required'],
            ['email', 'email'],
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
            'subject' => 'Тема',
            'body' => 'Содержимое',
            'city' => 'Город',
            'verifyCode' => 'Введите код',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {

        if ($this->validate()) {
            $params = ArrayHelper::toArray($this);
            Mailer::sendToAdmin($params);
            return true;
        }
        return false;
    }
}
