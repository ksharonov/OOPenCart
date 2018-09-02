<?php

use yii\db\Migration;

/**
 * Class m171212_054324_add_user_role_to_users
 */
class m171212_054324_add_user_role_to_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert("auth_assignment", [
            "item_name" => "user",
            "user_id" => "1",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171212_054324_add_user_role_to_users cannot be reverted.\n";

        return false;
    }
    */
}
