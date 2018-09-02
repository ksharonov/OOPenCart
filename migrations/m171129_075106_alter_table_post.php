<?php

use yii\db\Migration;

/**
 * Class m171129_075106_alter_table_post
 */
class m171129_075106_alter_table_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post', 'categoryId', $this->integer()->notNull()
            ->comment('Ид категории'));

        $this->dropTable('post_to_category');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->createTable('posts_categories', [
            'id' => $this->primaryKey(),
            'postId' => $this->integer()->notNull(),
            'categoryId' => $this->integer()->notNull()
        ]);

        $this->dropColumn('post', 'categoryId');


        return true;
    }
}
