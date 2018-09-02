<?php

use yii\db\Migration;

/**
 * Class m180222_103653_alter_table_post_category_id_to_null
 */
class m180222_103653_alter_table_post_category_id_to_null extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('post', 'categoryId', $this->integer()->null()
            ->comment('Ид категории'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('post', 'categoryId', $this->integer()->notNull()
            ->comment('Ид категории'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180222_103653_alter_table_post_category_id_to_null cannot be reverted.\n";

        return false;
    }
    */
}
