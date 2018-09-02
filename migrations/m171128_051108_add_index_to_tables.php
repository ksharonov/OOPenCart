<?php

use yii\db\Migration;

/**
 * Class m171128_051108_add_index_to_tables
 */
class m171128_051108_add_index_to_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('product_to_category', 'productId', $this->integer());
        $this->alterColumn('product_to_category', 'categoryId', $this->integer());

        $this->createIndex("page-title", "page", "title");

        $this->createIndex("post-title", "post", "title");
        $this->createIndex("post-status", "post", "status");

        $this->createIndex("post-category-title", "post_category", "title");
        $this->createIndex("post-category-parent-id", "post_category", "parentId");

        $this->createIndex("post-to-category-post-id", "post_to_category", "postId");
        $this->createIndex("post-to-category-category-id", "post_to_category", "categoryId");

        $this->createIndex("product-title", "product", "title");

        $this->createIndex("product-category-title", "product_category", "title");

        $this->createIndex("product-to-category-product-id", "product_to_category", "productId");
        $this->createIndex("product-to-category-category-id", "product_to_category", "categoryId");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex("page-title", "page");

        $this->dropIndex("post-title", "post");
        $this->dropIndex("post-status", "post");

        $this->dropIndex("post-category-title", "post_category");
        $this->dropIndex("post-category-parent-id", "post_category");

        $this->dropIndex("post-to-category-post-id", "post_to_category");
        $this->dropIndex("post-to-category-category-id", "post_to_category");

        $this->dropIndex("product-title", "product");

        $this->dropIndex("product-category-title", "product_category");

        $this->dropIndex("product-to-category-product-id", "product_to_category");
        $this->dropIndex("product-to-category-category-id", "product_to_category");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_051108_add_index_to_tables cannot be reverted.\n";

        return false;
    }
    */
}
