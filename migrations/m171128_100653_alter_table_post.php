<?php

use yii\db\Migration;

/**
 * Class m171128_100653_alter_table_post
 */
class m171128_100653_alter_table_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('post', 'content', $this->text()->comment('Контент'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('post', 'content', $this->string()->comment('Контент'));

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_100653_alter_table_post cannot be reverted.\n";

        return false;
    }
    */
}
