<?php

use yii\db\Migration;

/**
 * Class m180419_053233_add_column_discount_to_order_content
 */
class m180419_053233_add_column_discount_to_order_content extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order_content', 'discountValue', $this->double()->comment('Размер скидки на единицу товара'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order_content', 'discountValue');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_053233_add_column_discount_to_order_content cannot be reverted.\n";

        return false;
    }
    */
}
