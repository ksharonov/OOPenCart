<?php

use yii\db\Migration;

/**
 * Class m171129_084656_alter_table_post_category
 */
class m171129_084656_alter_table_post_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('post_category', 'parentId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('post_category', 'parentId', $this->integer()->notNull()
            ->comment('Родительская категория'));

        return true;
    }
}
