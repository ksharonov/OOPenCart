<?php

use yii\db\Migration;

/**
 * Class m171127_104030_create_table_posts_categories
 */
class m171127_104030_create_table_posts_categories extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('posts_categories', [
            'id' => $this->primaryKey(),
            'postId' => $this->integer()->notNull(),
            'categoryId' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('posts_categories');
    }
}
