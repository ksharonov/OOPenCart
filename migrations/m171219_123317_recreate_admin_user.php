<?php

use yii\db\Migration;

/**
 * Class m171219_123317_recreate_admin_user
 */
class m171219_123317_recreate_admin_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('client', 'email', $this->string(255)->comment('Почта клиента'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('client', 'email');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171219_123317_recreate_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
