<?php

use yii\db\Migration;

/**
 * Class m180129_053822_alter_columns_client_user
 */
class m180129_053822_alter_columns_client_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('client', 'email');
        $this->alterColumn('client', 'phone', $this->string(32)->comment('Номер телефона'));
        $this->alterColumn('user', 'phone', $this->string(32)->comment('Номер телефона'));
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
        echo "m180129_053822_alter_columns_client_user cannot be reverted.\n";

        return false;
    }
    */
}
