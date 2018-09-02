<?php

use yii\db\Migration;

/**
 * Class m171127_100937_create_table_categories
 */
class m171127_100937_create_table_categories extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('postsCategories', [
            'id' => $this->primaryKey()->comment('id категории'),
            'name' => $this->string(255)->notNull()->comment('Имя'),
            'slug' => $this->string(255)->notNull()->comment('Слаг'),
            'parentId' => $this->integer()->comment('id родительской категории'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('postsCategories');
        return true;
    }
}
