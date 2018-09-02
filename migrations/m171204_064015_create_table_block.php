<?php

use yii\db\Migration;

/**
 * Class m171204_064015_create_table_block
 */
class m171204_064015_create_table_block extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('block', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название'),
            'key' => $this->string('128')->comment('Ключ блока'),
            'value' => 'mediumtext COMMENT "Содержимое"',
            'type' => $this->integer()->comment('Тип'),
            'dtUpdate' => $this->timestamp()->comment('Дата обновления'),
            'dtCreate' => $this->timestamp()->comment('Дата создания')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('block');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171204_064015_create_table_block cannot be reverted.\n";

        return false;
    }
    */
}
