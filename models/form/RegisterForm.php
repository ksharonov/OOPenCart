<?php

namespace app\models\form;

use app\models\base\Mailer;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use app\models\db\User;

/**
 * Class RegisterForm
 *
 * Класс формы регистрации
 *
 * @package app\models\form
 */
class RegisterForm extends Model
{
    public $username;
    public $password;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Email',
            'password' => 'Пароль'
        ];
    }

    /**
     * Процесс регистрации нового пользователя
     * @return bool
     */
    public function register()
    {
        if ($this->getUser()) {
            return false;
        }

        $model = new User();
        $model->scenario = 'register';
        $model->username = $this->username;
        $model->password = $this->password;

        if ($this->isEmail()) {
            $model->email = $this->username;
        }

        if ($model->validate() && $model->save()) {
            $loginModel = new LoginForm();
            $loginModel->username = $this->username;
            $loginModel->password = $this->password;

            $this->_user = $model;

            $this->successMailProcess();

            return $loginModel->login();

        }
        return false;
    }

    /**
     * @return User|bool
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Если логин - почта, то добавляем как почту
     */
    public function isEmail()
    {
        return strpos($this->username, '@') !== false;
    }

    /**
     * Отправка сообщения на почту
     */
    public function successMailProcess()
    {
        if ($this->isEmail()) {
            try {
                Mailer::sendToAdmin([
                    'email' => $this->username,
                    'name' => $this->username,
                    'subject' => 'Регистрация пользователя',
                    'body' => 'На сайте зарегистрировался новый пользователь: ' . $this->username
                ]);
            } catch (Exception $e) {

            }
        }
    }
}
