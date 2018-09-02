<?php

namespace app\models\form;

use app\helpers\MailHelper;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\base\Mailer;
use app\models\db\User;

/**
 * Модель формы восстановления пароля
 * @property string $username
 * @property User $user
 *
 */
class RestorePasswordForm extends Model
{
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Email'
        ];
    }

    /**
     * Проверка на наличие пользователя и отправка сообщения
     *
     * @return void
     */
    public function check()
    {
        $user = $this->user;
        if ($user) {
            $key = Yii::$app->security->generateRandomString(32);
            $user->changePasswordKey = $key;
            $user->save(false);

            $regUrl = Yii::$app->urlManager->createAbsoluteUrl(["/", 'key' => $key]);

            MailHelper::restorePassword($this, $regUrl);
        }
    }

    /**
     * Возвращает профиль пользователя
     *
     * @return ActiveRecord|bool
     */
    public function getUser()
    {
        return User::find()
                ->where(['username' => $this->username])
                ->orWhere(['email' => $this->username])
                ->one() ?? false;
    }
}