<?php

use yii\db\Migration;

/**
 * Class m171220_061202_add_status_to_order
 */
class m171220_061202_add_status_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'status', $this->integer()->comment('Статус заказа'));
        $this->addColumn('order', 'paymentStatus', $this->integer()->comment('Статус оплаты'));

        $this->createIndex('order-status', 'order', 'status');
        $this->createIndex('order-payment-status', 'order', 'paymentStatus');

        $this->createIndex('user-status', 'user', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'status');
        $this->dropColumn('order', 'paymentStatus');
        $this->dropIndex('user-status', 'user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_061202_add_status_to_order cannot be reverted.\n";

        return false;
    }
    */
}
