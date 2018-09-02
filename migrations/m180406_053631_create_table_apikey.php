<?php

use yii\db\Migration;

/**
 * Class m180406_053631_create_table_apikey
 */
class m180406_053631_create_table_apikey extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('apikey', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(11),
            'clientId' => $this->integer(11),
            'keyValue' => $this->string(50),
            'dtCreate' => $this->timestamp(),
            'duration' => $this->integer()->comment('Длительность действия в часах'),
            'status' => $this->integer(),
            'permission' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('apikey');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180406_053631_create_table_apikey cannot be reverted.\n";

        return false;
    }
    */
}
