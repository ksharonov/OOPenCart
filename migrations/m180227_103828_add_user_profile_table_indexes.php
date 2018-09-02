<?php

use yii\db\Migration;

/**
 * Class m180227_103828_add_user_profile_table_indexes
 */
class m180227_103828_add_user_profile_table_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('userId_idx', 'user_profile', 'userId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('userId_idx', 'user_profile');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_103828_add_user_profile_table_indexes cannot be reverted.\n";

        return false;
    }
    */
}
