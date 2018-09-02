<?php

use yii\db\Migration;

/**
 * Class m171221_062024_add_orderStatus_to_order
 */
class m171221_062024_add_orderStatus_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'orderStatus', $this->integer()->comment('Статус заказа'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'orderStatus');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171221_062024_add_orderStatus_to_order cannot be reverted.\n";

        return false;
    }
    */
}
