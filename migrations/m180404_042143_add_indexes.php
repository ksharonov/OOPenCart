<?php

use yii\db\Migration;

/**
 * Class m180404_042143_add_indexes
 */
class m180404_042143_add_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('product_price_group_clientType_idx', 'product_price_group', 'clientType');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('product_price_group_clientType_idx', 'product_price_group');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180404_042143_add_indexes cannot be reverted.\n";

        return false;
    }
    */
}
