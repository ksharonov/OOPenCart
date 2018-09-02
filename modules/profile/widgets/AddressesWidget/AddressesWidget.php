<?php

namespace app\modules\profile\widgets\AddressesWidget;

use app\components\ClientComponent;
use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\UserToClient;
use app\models\form\ClientAddressForm;
use Yii;
use yii\base\Widget;
use app\models\db\User;

/**
 * Class AccessWidget
 *
 * Виджет разрешений пользователей клиента на странице пользователя
 *
 * @package app\modules\profile\widgets\AccessWidget
 */
class AddressesWidget extends Widget
{
    public function run()
    {
        $view = $this->getView();
        AddressesAsset::register($view);
        /** @var User $user */
        $user = Yii::$app->user->identity;
        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;

        $client = $user->client;

        $addresses = Address::find()
            ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
            ->andWhere(['relModelId' => $client->id])
            ->all();

        return $this->render('index', [
            'user' => $user,
            'client' => $client,
            'addresses' => $addresses
        ]);
    }
}