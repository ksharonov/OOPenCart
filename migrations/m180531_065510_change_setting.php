<?php

use yii\db\Migration;

/**
 * Class m180531_065510_change_setting
 */
class m180531_065510_change_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ufaId = \app\models\db\City::findOne(['title' => 'Уфа'])->id ?? 403;
        $neftId = \app\models\db\City::findOne(['title' => 'Нефтекамск'])->id ?? 414;
        \app\models\db\Setting::set('DELIVERY.COST.FREE', '[{"cityId":"' . $ufaId . '","minPriceForFree":"5000","deliveryCost":"500"},{"cityId":"' . $neftId . '","minPriceForFree":"5000","deliveryCost":"500"}]');
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
        echo "m180531_065510_change_setting cannot be reverted.\n";

        return false;
    }
    */
}
