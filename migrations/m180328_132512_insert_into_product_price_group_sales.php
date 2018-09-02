<?php

use yii\db\Migration;

/**
 * Class m180328_132512_insert_into_product_price_group_sales
 */
class m180328_132512_insert_into_product_price_group_sales extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('product_price_group', ['title' => 'Распродажа', 'priority' => 99]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180328_132512_insert_into_product_price_group_sales cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180328_132512_insert_into_product_price_group_sales cannot be reverted.\n";

        return false;
    }
    */
}
