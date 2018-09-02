<?php

namespace app\extensions\delivery\DeliveryTransportCompanyExtension;

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
class DeliveryTransportCompanyExtension extends DeliveryExtension
{
    /** Префикс */
    const EXTENSION_ATTR = 'deliveryData';

    /** @var string Класс модели */
    public $extensionModelClass = 'app\extensions\delivery\DeliveryTransportCompanyExtension\models\WidgetModel';

    /** @var Model */
    public $extensionModel;

    /** @var array список транспортных компаний */
    public $transportCompanies = [
        'Энергия' => 'Энергия',
        'ПЭК' => 'ПЭК'
    ];

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
        } else {

        }

        if (!$this->transportCompanies) {
            return null;
        }

        return $this->render($this->_view, [
            'id' => $this->id,
            'order' => $this->order,
            'form' => $this->form,
            'extensionParams' => $this->extensionParams,
            'transportCompanies' => $this->transportCompanies
        ]);
    }
}