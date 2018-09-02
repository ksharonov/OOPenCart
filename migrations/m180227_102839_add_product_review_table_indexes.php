<?php

use yii\db\Migration;

/**
 * Class m180227_102839_add_product_review_table_indexes
 */
class m180227_102839_add_product_review_table_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('userId_idx', 'product_review', 'userId');
        $this->createIndex('productId_idx', 'product_review', 'productId');
        $this->createIndex('rating_idx', 'product_review', 'rating');
        $this->createIndex('productId_userId_idx', 'product_review', ['productId', 'userId']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('userId_idx', 'product_review');
        $this->dropIndex('productId_idx', 'product_review');
        $this->dropIndex('rating_idx', 'product_review');
        $this->dropIndex('productId_userId_idx', 'product_review');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_102839_add_product_review_table_indexes cannot be reverted.\n";

        return false;
    }
    */
}
