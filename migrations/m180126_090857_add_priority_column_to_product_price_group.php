<?php

use yii\db\Migration;

/**
 * Class m180126_090857_add_priority_column_to_product_price_group
 */
class m180126_090857_add_priority_column_to_product_price_group extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_price_group', 'priority', $this->integer()->comment('Приоритет цены'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_price_group', 'priority');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180126_090857_add_priority_column_to_product_price_group cannot be reverted.\n";

        return false;
    }
    */
}
