<?php

use yii\db\Migration;

/**
 * Class m180503_064539_add_column_userId_to_post
 */
class m180503_064539_add_column_userId_to_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post', 'userId', $this->integer());
//        $this->createIndex('idx-user-post', 'post', 'userId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
//        $this->dropIndex('idx-user-post', 'post');
        $this->dropColumn('post', 'userId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180503_064539_add_column_userId_to_post cannot be reverted.\n";

        return false;
    }
    */
}
