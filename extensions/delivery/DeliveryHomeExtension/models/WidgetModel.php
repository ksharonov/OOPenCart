<?php

namespace app\extensions\delivery\DeliveryHomeExtension\models;

use yii\base\DynamicModel;
//use yii\base\Model;
use app\system\base\Model;

class WidgetModel extends Model
{
    public $city;
    public $home;
    public $street;
    public $room;
    public $comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city', 'street', 'home', 'room', 'comment'], 'string'],
//            [['city'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city' => 'Город',
            'street' => 'Улица',
            'home' => 'Дом',
            'room' => 'Квартира',
            'comment' => 'Комментарий'
        ];
    }

    public function getText(array $data)
    {
        $data['city'] = $data['city'] ?? null;
        $data['street'] = $data['street'] ?? null;
        $data['room'] = $data['room'] ?? null;
        return "Доставка по адресу г. {$data['city']}, ул. {$data['street']} {$data['home']}, кв. {$data['room']}";
    }
}