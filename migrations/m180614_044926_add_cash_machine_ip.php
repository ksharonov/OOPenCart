<?php

use yii\db\Migration;

/**
 * Class m180614_044926_add_cash_machine_ip
 */
class m180614_044926_add_cash_machine_ip extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'CASH.MACHINE.IP',
            'setValue' => '192.168.10.116:4444',
            'type' => 0,
            'title' => 'Адрес кассового аппарата'
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
        echo "m180614_044926_add_cash_machine_ip cannot be reverted.\n";

        return false;
    }
    */
}
