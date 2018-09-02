<?php

use yii\db\Migration;

/**
 * Class m171129_110838_create_table_client
 */
class m171129_110838_create_table_client extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey()->comment('id клиента'),
            'title' => $this->string(255)->comment('Название'),
            'type' => $this->integer()->comment('Тип клиента'),
            'status' => $this->integer()->comment('Статус клиента'),
            'dtUpdate' => $this->timestamp()->comment('Дата обновления'),
            'dtCreate' => $this->timestamp()->comment('Дата создания')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('client');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171129_110838_create_table_client cannot be reverted.\n";

        return false;
    }
    */
}
