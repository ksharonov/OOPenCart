<?php

use yii\db\Migration;

/**
 * Class m171201_063910_create_table_orders
 */
class m171201_063910_create_table_orders extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'clientId' => $this->integer()->comment('id клиента'),
            'userId' => $this->integer()->comment('id пользователя'),
            'addressId' => $this->integer()->comment('id адреса'),
            'comment' => $this->text()->comment('Комментарий'),
            'products' => 'json DEFAULT NULL',
            'source' => $this->smallInteger()->comment('Источник данных')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('orders');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_063910_create_table_orders cannot be reverted.\n";

        return false;
    }
    */
}
