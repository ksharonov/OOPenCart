<?php

use yii\db\Migration;
use app\models\db\Setting;

/**
 * Class m180424_044833_insert_setting_for_storage_balance
 */
class m180424_044833_insert_setting_for_storage_balance extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'STORAGE.BALANCE.GUEST.HIDE',
            'setValue' => 0,
            'type' => Setting::TYPE_TEXT,
            'title' => 'Скрывать наличие по складам для гостей',
        ]);
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
        echo "m180424_044833_insert_setting_for_storage_balance cannot be reverted.\n";

        return false;
    }
    */
}
