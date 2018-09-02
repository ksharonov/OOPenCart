<?php

use yii\db\Migration;

/**
 * Class m171205_053401_create_table_setting
 */
class m171205_053401_create_table_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('setting', [
            'id' => $this->primaryKey(),
            'setKey' => $this->string(48)->comment('Ключ'),
            'setValue' => $this->string(1024)->comment('Значение'),
            'type' => $this->integer()->comment('Тип')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('setting');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171205_053401_create_table_setting cannot be reverted.\n";

        return false;
    }
    */
}
