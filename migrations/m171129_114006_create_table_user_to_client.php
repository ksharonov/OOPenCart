<?php

use yii\db\Migration;

/**
 * Class m171129_114006_create_table_user_to_client
 */
class m171129_114006_create_table_user_to_client extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user_to_client', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull()->comment('Ид пользователя'),
            'clientId' => $this->integer()->notNull()->comment('Ид клиента')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('user_to_client');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171129_114006_create_table_user_to_client cannot be reverted.\n";

        return false;
    }
    */
}
