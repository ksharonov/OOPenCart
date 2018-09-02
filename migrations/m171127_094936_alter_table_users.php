<?php

use yii\db\Migration;

/**
 * Class m171127_094936_alter_table_users
 */
class m171127_094936_alter_table_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('users', 'uid', 'id');
        $this->renameColumn('users', 'access_token', 'accessToken');
        $this->renameColumn('users', 'change_password_key', 'changePasswordKey');
        $this->renameColumn('users', 'dtcreate', 'dtCreate');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('users', 'id', 'uid');
        $this->renameColumn('users', 'accessToken', 'access_token');
        $this->renameColumn('users', 'changePasswordKey', 'change_password_key');
        $this->renameColumn('users', 'dtCreate', 'dtcreate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171127_094936_alter_table_users cannot be reverted.\n";

        return false;
    }
    */
}
