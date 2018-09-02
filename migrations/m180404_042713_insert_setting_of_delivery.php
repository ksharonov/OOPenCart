<?php

use yii\db\Migration;
use app\models\db\City;

/**
 * Class m180404_042713_insert_setting_of_delivery
 */
class m180404_042713_insert_setting_of_delivery extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Параметрые бесплатной доставки',
            'setKey' => 'DELIVERY.COST.FREE',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);

        $param = [];

        $ufaCity = City::findByName('Уфа');
        $neftCity = City::findByName('Нефтекамск');

        $param[$ufaCity->id] = [
            'minPriceForFree' => 5000,
            'deliveryCost' => 500
        ];

        $param[$neftCity->id] = [
            'minPriceForFree' => 5000,
            'deliveryCost' => 500
        ];

        \app\models\db\Setting::set('DELIVERY.COST.FREE', \yii\helpers\Json::encode($param));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180404_042713_insert_setting_of_delivery cannot be reverted.\n";

        return false;
    }
    */
}
