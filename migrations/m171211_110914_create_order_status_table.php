<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_status`.
 */
class m171211_110914_create_order_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_status', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ]);

        $this->createIndex('order-status-title', 'order_status', 'title');

        $this->createTable('order_status_history', [
            'id' => $this->primaryKey(),
            'orderId' => $this->integer(),
            'orderStatusId' => $this->integer(),
            'dtCreate' => $this->timestamp()
        ]);

        $this->createIndex('order-status-history-order', 'order_status_history', 'orderId');
        $this->createIndex('order-status-history-status', 'order_status_history', 'orderStatusId');
        $this->createIndex('order-status-history-search', 'order_status_history', ['orderId', 'orderStatusId']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_status');
        $this->dropTable('order_status_history');
    }
}
