<?php

namespace app\extensions\delivery\DeliveryHomeExtension;

use app\models\db\Extension;
use app\system\base\Model;
use kartik\form\ActiveForm;
use yii\base\Widget;
use app\system\extension\DeliveryExtension;

/**
 * Class NewDeliveryExtension
 *
 * Способ получения товара доставкой на дом
 *
 * @package app\extensions\delivery\DeliveryHomeExtension
 */
class DeliveryHomeExtension extends DeliveryExtension
{
    /** Префикс */
    const EXTENSION_ATTR = 'deliveryData';

    /** @var string Класс модели */
    public $extensionModelClass = 'app\extensions\delivery\DeliveryHomeExtension\models\WidgetModel';

    /** @var Model */
    public $extensionModel;

    public function run()
    {
        $this->modelPrepare();

        return $this->render($this->_view, [
            'model' => $this->extensionModel,
            'id' => $this->id,
            'order' => $this->order,
            'form' => $this->form,
            'extensionParams' => $this->extensionParams
        ]);
    }

    public function modelPrepare()
    {
        /** @var Model $model */
        $model = new $this->extensionModelClass();
        $model->formName = $this->order->formName() . "[" . self::EXTENSION_ATTR . "][$this->id]";
        $model->validate();

        $this->extensionModel = $model;
    }
}