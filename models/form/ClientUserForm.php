<?php

namespace app\models\form;

use app\models\base\Mailer;
use app\models\db\UserProfile;
use app\models\db\UserToClient;
use yii\base\Model;
use app\models\db\User;

/**
 * Class ClientUserForm
 *
 * Форма добавления пользователя с клиентского меню
 *
 * @package app\models\form
 */
class ClientUserForm extends Model
{

    /**
     * @var null
     */
    public $id = null;

    /**
     * @var
     */
    public $username;

    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $fio;

    /**
     * @var
     */
    public $phone;

    /**
     *
     */
    public $password = null;

    /**
     * @var User|boolean
     */
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id', 'password'], 'string'],
            [['username', 'email', 'fio', 'phone'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * Регистрация пользователя
     * @return bool
     */
    public function register()
    {
        /** @var User $userComponent */
        $userComponent = \Yii::$app->user->identity;
        $this->username = $this->email;
        if ($this->validate() && !$this->getUser()) {

            if ((bool)$this->id) {
                $userModel = User::findOne(['id' => $this->id]);
            } else {
                $userModel = new User();
            }

            $userModel->load(['User' => $this->attributes]);
            $userModel->password = $this->password ?? \Yii::$app->security->generateRandomString(9);
            $userModel->fromRemote = true;
            if ($userModel->save() && !(bool)$this->id) {
                $userProfile = new UserProfile();
                $userProfile->userId = $userModel->id;
                $userProfile->save();

                UserToClient::bind($userModel, $userComponent->client, UserToClient::POS_DEFAULT);

                $auth = \Yii::$app->authManager;
                $userRole = $auth->getRole('user');
                $auth->assign($userRole, $userModel->id);

                Mailer::send([
                    'user' => $userModel,
                    'title' => 'Регистрация пользователя',
                    'message' => "Регистрация нового пользователя {$userModel->email} с паролем {$this->password}"
                ]);

                return true;
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false && !(bool)$this->id) {
            $this->_user = User::findByUsername($this->username) ?? User::findByEmail($this->email);
        }

        return $this->_user;
    }
}