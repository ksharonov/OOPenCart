<?php

use yii\db\Migration;

/**
 * Class m171208_051851_create_table_storage_balance
 */
class m171208_051851_create_table_storage_balance extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('storage_balance', [
            'id' => $this->primaryKey(),
            'storageId' => $this->integer()->comment('Склад'),
            'productId' => $this->integer()->comment('Продукт'),
            'quantity' => $this->integer()->comment('Количество')
        ]);

        $this->createIndex('storage-title', 'storage', 'title');

        $this->createIndex('storage-balance-storage', 'storage_balance', 'storageId');
        $this->createIndex('storage-balance-product', 'storage_balance', 'productId');
        $this->createIndex('storage-balance-quantity', 'storage_balance', ['productId', 'storageId', 'quantity']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('storage_balance');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_051851_create_table_storage_balance cannot be reverted.\n";

        return false;
    }
    */
}
