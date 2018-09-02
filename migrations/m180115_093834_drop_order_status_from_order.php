<?php

use yii\db\Migration;

/**
 * Class m180115_093834_drop_order_status_from_order
 */
class m180115_093834_drop_order_status_from_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('order', 'orderStatus');
        $this->addColumn('order', 'paymentData', $this->text()->comment('Данные по оплате'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('order', 'orderStatus', $this->integer()->comment('Статус заказа'));
        $this->dropColumn('order', 'paymentData');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_093834_drop_order_status_from_order cannot be reverted.\n";

        return false;
    }
    */
}
