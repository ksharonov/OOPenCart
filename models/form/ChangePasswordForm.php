<?php

namespace app\models\form;

use yii\base\Model;
use app\models\db\User;
use yii\db\ActiveRecord;

/**
 * Модель формы смены пароля
 * @property string $username
 * @property string $key
 * @property User $user
 *
 */
class ChangePasswordForm extends Model
{
    public $key;
    public $password;
//    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'password'], 'required'], //', 'passwordRepeat'
            [['password'], 'string', 'max' => 255],//, 'passwordRepeat'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Ключ восстановления пароля',
            'password' => 'Пароль',
//            'passwordRepeat' => 'Повторите пароль'
        ];
    }

    /**
     * Смена пароля
     *
     * @return bool
     */
    public function change()
    {
        $user = $this->user;

        if ($user && $this->check()) {
            $user->password = $this->password;
            $user->repeatPassword = $this->password;
            $user->changePasswordKey = null;
            return $user->save();
        } else {
            return false;
        }
    }

    /**
     * Проверка пароля
     *
     * @return bool
     */
    public function check()
    {
        return $this->password == $this->password; //$this->passwordRepeat;
    }

    /**
     * Возвращает профиль пользователя
     *
     * @return ActiveRecord|bool
     */
    public function getUser()
    {
        return User::find()
                ->where(['changePasswordKey' => $this->key])
                ->one() ?? false;
    }
}