<?php

namespace app\widgets\AuthFormsWidget;

use app\models\db\User;
use Yii;
use yii\helpers\Url;
use yii\base\Widget;
use app\models\form\LoginForm;
use app\models\form\RegisterForm;
use app\models\form\ChangePasswordForm;
use app\models\form\RestorePasswordForm;

/**
 * Class AuthFormsWidget
 *
 * Формы авторизации, регистрации, и т.д..
 *
 * @package app\widgets\AuthFormsWidget
 */
class AuthFormsWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        AuthFormsAsset::register($this->getView());
        $loginForm = new LoginForm();
        $registerForm = new RegisterForm();
        $restorePasswordForm = new RestorePasswordForm();
        $changePasswordForm = new ChangePasswordForm();

        $key = Yii::$app->request->get('key') ?? null;

        if ($key && !User::findOne(['changePasswordKey' => $key])) {
            $key = false;
        }

        $html = "";

        $html .= $this->render('login', [
            'model' => $loginForm
        ]);

        $html .= $this->render('register', [
            'model' => $registerForm
        ]);

        $html .= $this->render('restore', [
            'model' => $restorePasswordForm
        ]);

        $html .= $this->render('change', [
            'model' => $changePasswordForm,
            'key' => $key
        ]);

        return $html;
    }
}