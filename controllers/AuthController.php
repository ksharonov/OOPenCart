<?php
namespace app\controllers;

use app\models\cookie\CartCookie;
use app\models\db\Setting;
use app\models\form\RegisterForm;
use Yii;
use app\models\db\User;
use app\system\base\Controller;
use app\models\form\LoginForm;
use app\models\form\RestorePasswordForm;
use app\models\form\ChangePasswordForm;
use yii\helpers\Url;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\system\template\TemplateStore;

class AuthController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect('/');
        }

        TemplateStore::setVar("CONTAINER.LAYOUT.SITE.CLASS", 'container');
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Авторизация пользователя
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $previousUrl = Url::previous();
        $params = Yii::$app->request->get('LoginForm');
        $params['rememberMe'] = $params['rememberMe'] ?? false;

        if (!Yii::$app->user->isGuest) {
            return false;
        }

        $model = new LoginForm();

        //todo поправить
        if ($params['rememberMe'] == 'on') {
            $params['rememberMe'] = true;
        } else {
            $params['rememberMe'] = false;
        }

        if ($model->load(['LoginForm' => $params]) && $model->login()) {
            return $this->redirect($previousUrl);
        } else {
            return 'Error';
        }
    }

    /**
     * Регистрация нового пользователя
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return true;
        }

        return false;
    }

    /**
     * Форма восстановления пароля
     *
     * @return Response|string
     */
    public function actionRestorePassword()
    {
        if (!Yii::$app->request->isPost) {
            return $this->redirect('/');
        }

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RestorePasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->check();
            Yii::$app->session->setFlash('restore-password', 'Сообщение отправлено');
            return true;
        }

        return false;
    }

    /**
     * Форма смены пароля
     *
     * @return Response|string
     */
    public function actionChangePassword()
    {

        if (!Yii::$app->request->isPost) {
            return $this->redirect('/');
        }

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->change()) {
            Yii::$app->session->setFlash('change-password', 'Пароль изменён');
            return $this->goHome();
        }
        return $this->redirect('/');
//        return $this->render('change-password', [
//            'model' => $model
//        ]);
    }

    /**
     * Разлогин пользователя
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        if (Setting::get('CART.CLEAR.AFTER.LOGOUT')) {
            CartCookie::deleteAll();
        }
        return $this->goHome();
    }
}