<?php

namespace app\extensions\delivery\DeliveryPickupExtension\models;

use yii\base\Model;

class WidgetModel extends Model
{
    public function getText(array $data)
    {
        return $data['title'] ?? null;
    }
}