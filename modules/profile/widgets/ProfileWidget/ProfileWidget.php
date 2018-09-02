<?php

namespace app\modules\profile\widgets\ProfileWidget;

use app\components\ClientComponent;
use app\system\template\TemplateBase;
use Yii;
use app\models\db\User;
use yii\base\Widget;
use app\models\db\Client;
use yii\helpers\Url;

//use app\system\base\Widget;

/*
 * Виджет данных пользователя на странице профиля
 */

class ProfileWidget extends Widget
{

    public $id = "profile";

    public $moduleId = TemplateBase::MODULE_PROFILE;

    /**
     * Авторизация пользователя
     *
     * @return string
     */
    public function run()
    {
        $view = $this->getView();
        ProfileAsset::register($view);
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $client = $user->client;

        if ($user->load(Yii::$app->request->post()) && $user->save()) {

        }


        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;

        if ($clientComponent->isIndividual()) {
            $viewName = 'individual';
        } elseif ($clientComponent->isEntity()) {
            $viewName = 'entity';
        } else {
            return Yii::$app->getResponse()->redirect(Url::to('/'));
        }

        return $this->render($viewName, [
            'user' => $user,
            'client' => $client
        ]);
    }
}