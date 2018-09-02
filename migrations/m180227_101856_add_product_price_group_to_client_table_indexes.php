<?php

use yii\db\Migration;

/**
 * Class m180227_101856_add_product_price_group_to_client_table_indexes
 */
class m180227_101856_add_product_price_group_to_client_table_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('productPriceGroupId_idx', 'product_price_group_to_client', 'productPriceGroupId');
        $this->createIndex('clientId_idx', 'product_price_group_to_client', 'clientId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('productPriceGroupId_idx', 'product_price_group_to_client');
        $this->dropIndex('clientId_idx', 'product_price_group_to_client');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_101856_add_product_price_group_to_client_table_indexes cannot be reverted.\n";

        return false;
    }
    */
}
