<?php

use yii\db\Migration;

/**
 * Class m171220_084418_add_paymentMethod_to_order
 */
class m171220_084418_add_paymentMethod_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'paymentMethod', $this->integer()->comment('Метод оплаты'));
        $this->createIndex('order-payment-method', 'order', 'paymentMethod');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'paymentMethod');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_084418_add_paymentMethod_to_order cannot be reverted.\n";

        return false;
    }
    */
}
