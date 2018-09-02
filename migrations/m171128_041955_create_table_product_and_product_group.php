<?php

use yii\db\Migration;

/**
 * Class m171128_041955_create_table_product_and_product_group
 */
class m171128_041955_create_table_product_and_product_group extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey()->comment('id товара'),
            'title' => $this->char(255)->comment('Название'),
            'content' => $this->text()->comment('Содержимое'),
        ]);

        $this->createTable('product_category', [
            'id' => $this->primaryKey()->comment('id товара'),
            'title' => $this->char(255),
            'content' => $this->text(),
        ]);

        $this->renameTable('users', 'user');
        $this->renameTable('posts', 'post');
        $this->renameTable('postsCategories', 'post_category');
        $this->renameTable('posts_categories', 'post_to_category');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product');
        $this->dropTable('product_category');
        $this->renameTable('user', 'users');
        $this->renameTable('post', 'posts');
        $this->renameTable('post_to_category', 'posts_categories');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_041955_create_table_product_and_product_group cannot be reverted.\n";

        return false;
    }
    */
}
