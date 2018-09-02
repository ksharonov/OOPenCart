<?php

use yii\db\Migration;

/**
 * Class m180326_120649_alter_table_order_content_price_value_and_count
 */
class m180326_120649_alter_table_order_content_price_value_and_count extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('order_content', 'priceValue', $this->decimal(9,2)->comment('Цена'));
        $this->alterColumn('order_content', 'count', $this->decimal(9, 3)->comment('Количество'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180326_120649_alter_table_order_content_price_value_and_count cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180326_120649_alter_table_order_content_price_value_and_count cannot be reverted.\n";

        return false;
    }
    */
}
