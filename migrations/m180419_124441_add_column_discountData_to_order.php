<?php

use yii\db\Migration;

/**
 * Class m180419_124441_add_column_discountData_to_order
 */
class m180419_124441_add_column_discountData_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'discountData', 'json DEFAULT NULL');
        $this->addCommentOnColumn('order', 'discountData', 'Скидочные данные');
        $this->addCommentOnTable('order', 'Заказы');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'discountData');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_124441_add_column_discountData_to_order cannot be reverted.\n";

        return false;
    }
    */
}
