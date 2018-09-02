<?php

use yii\db\Migration;

/**
 * Class m180405_125919_insert_into_setting_main_brands_list
 */
class m180405_125919_insert_into_setting_main_brands_list extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $brandList = [3, 7, 8, 11, 12, 14];

        $this->insert('setting', [
            'setKey' => 'MAIN.BRAND.LIST',
            'setValue' => \yii\helpers\Json::encode($brandList),
            'type' => 0,
            'title' => 'Массив Id брендов, которые отображаются на главной',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('setting', ['setKey' => 'MAIN.BRAND.LIST']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_125919_insert_into_setting_main_brands_list cannot be reverted.\n";

        return false;
    }
    */
}
