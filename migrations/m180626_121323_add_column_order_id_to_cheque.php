<?php

use yii\db\Migration;

/**
 * Class m180626_121323_add_column_order_id_to_cheque
 */
class m180626_121323_add_column_order_id_to_cheque extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('cheque', 'orderId', $this->integer()->comment('Заказ'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('cheque', 'orderId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180626_121323_add_column_order_id_to_cheque cannot be reverted.\n";

        return false;
    }
    */
}
