<?php

use yii\db\Migration;

/**
 * Class m180412_094437_rename_table_to_lexema_order
 */
class m180412_094437_rename_table_to_lexema_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('order_lexema', 'lexema_order');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameTable('lexema_order', 'order_lexema');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_094437_rename_table_to_lexema_order cannot be reverted.\n";

        return false;
    }
    */
}
