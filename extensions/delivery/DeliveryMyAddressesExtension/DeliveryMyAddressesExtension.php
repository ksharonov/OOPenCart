<?php

namespace app\extensions\delivery\DeliveryMyAddressesExtension;

use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use Yii;
use app\components\ClientComponent;
use app\models\db\Storage;
use app\models\db\User;
use \Exception;
use app\models\db\Extension;
use yii\base\Widget;
use app\system\extension\DeliveryExtension;
use app\system\base\Model;

/**
 * Class DeliveryPickupExtension
 * Способ получения товара самовывозом
 *
 * @package app\extensions\delivery\DeliveryPickupExtension
 */
class DeliveryMyAddressesExtension extends DeliveryExtension
{
    /** Префикс */
    const EXTENSION_ATTR = 'deliveryData';

    public $extensionModelClass = 'app\extensions\delivery\DeliveryMyAddressesExtension\models\WidgetModel';

    /** @var Model $extensionModel */
    public $extensionModel;

    /**
     * @return string
     */
    public function run()
    {
        $fields = $this->fields;

        if ($this->order->deliveryData) {
            $fields = $this->order->deliveryData;
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;

        /** @var ClientComponent $clientComponent */
        $clientComponent = Yii::$app->client;

        if ($user) {
            $client = $user->client;
            if ($client) {
                $addresses = Address::find()
                    ->where(['relModel' => ModelRelationHelper::REL_MODEL_CLIENT])
                    ->andWhere(['relModelId' => $client->id])
                    ->all();
            } else {
                $addresses = [];
            }

        } else {
            $addresses = [];
        }
        if (!$addresses) {
            return null;
        }
        return $this->render($this->_view, [
            'id' => $this->id,
            'order' => $this->order,
            'form' => $this->form,
            'addresses' => $addresses,
            'extensionParams' => $this->extensionParams
        ]);
    }
}