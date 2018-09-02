<?php

use yii\db\Migration;

/**
 * Class m180112_114527_add_delivery_columns_to_order
 */
class m180112_114527_add_delivery_columns_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('order', 'paymentStatus');

        $this->addColumn('order', 'deliveryMethod', $this->integer()->comment('Способ доставки'));
        $this->addColumn('order', 'deliveryData', $this->text()->comment('Данные доставки'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'deliveryMethod');
        $this->dropColumn('order', 'deliveryData');

        $this->addColumn('order', 'paymentStatus', $this->integer()->comment('Статус оплаты'));
        $this->createIndex('order-payment-status', 'order', 'paymentStatus');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180112_114527_add_delivery_columns_to_order cannot be reverted.\n";

        return false;
    }
    */
}
