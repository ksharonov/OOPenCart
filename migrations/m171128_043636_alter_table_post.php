<?php

use yii\db\Migration;

/**
 * Class m171128_043636_alter_table_post
 */
class m171128_043636_alter_table_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('post', 'date', 'dtCreate');
        $this->addColumn('post', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('post', 'dtCreate', 'date');
        $this->dropColumn('post', 'dtUpdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_043636_alter_table_post cannot be reverted.\n";

        return false;
    }
    */
}
