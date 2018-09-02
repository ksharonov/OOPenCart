<?php

namespace app\components;

use yii\base\Component;
use app\models\db\User;
use app\models\db\Client;

/**
 * Class ClientComponent
 *
 * @package app\components
 *
 * todo чуть позже подумать над реализацией для консольного приложения
 *
 * @property Client $emptyClient
 */
class ClientComponent extends Component
{
    public $client;

    const EMPTY_CLIENT_TITLE = 'Незарегистрированный клиент';

    public function init()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        if ($user) {
            $this->client = $user->client;
        } else {
            $this->client = $this->emptyClient;
        }

        parent::init();
    }

    public function get()
    {
        return $this->client;
    }

    /**
     * Клиент - физическое лицо
     *
     * @return bool
     */
    public function isIndividual()
    {
        if (!isset($this->client->type)) {
            return true;
        }

        return $this->client->type == Client::TYPE_INDIVIDUAL;
    }

    /**
     * Клиент - юридическое лицо
     *
     * @return bool
     */
    public function isEntity()
    {
        if (!isset($this->client->type)) {
            return false;
        }
        return $this->client->type == Client::TYPE_ENTITY;
    }

    /**
     * Пустой клиент, если юзер не зарегистрирован
     *
     * @return Client
     */
    public function getEmptyClient()
    {
        $client = new Client();
        $client->type = Client::TYPE_INDIVIDUAL;
        $client->title = self::EMPTY_CLIENT_TITLE;
        return $client;
    }
}