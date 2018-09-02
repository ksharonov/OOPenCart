<?php

use yii\db\Migration;

/**
 * Class m171201_065147_rename_table_addresses_to_adress
 */
class m171201_065147_rename_table_addresses_to_adress extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('addresses', 'address');
        $this->renameTable('orders', 'order');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameTable('address', 'addresses');
        $this->renameTable('order', 'orders');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_065147_rename_table_addresses_to_adress cannot be reverted.\n";

        return false;
    }
    */
}
