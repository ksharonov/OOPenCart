<?php

use yii\db\Migration;

/**
 * Class m171127_072157_insert_admin_to_users
 */
class m171127_072157_insert_admin_to_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->delete('users');
        $this->delete('auth_assignment');
        $this->delete('auth_item');
        $this->delete('auth_item_child');

        $this->execute("ALTER TABLE users AUTO_INCREMENT=0");

        /*
        * Роли пользователей
        */

        $this->insert("auth_item", [
            "name" => "admin",
            "type" => "1",
            "description" => "Администратор"
        ]);
        $this->insert("auth_item", [
            "name" => "admin.full",
            "type" => "2",
            "description" => "Администратор: Полный доступ"
        ]);
        $this->insert("auth_item", [
            "name" => "manager",
            "type" => "1",
            "description" => "Менеджер"
        ]);
        $this->insert("auth_item", [
            "name" => "user",
            "type" => "1",
            "description" => "Пользователь"
        ]);

        $this->insert("auth_item_child", [
            "parent" => "admin",
            "child" => "admin.full",
        ]);

        /*
        * Пользователи
        */


        $this->insert("users", [
            "username" => "admin",
            "password" => '$2y$13$s1lIGkrQ74y33oVOrJffLOX.gGbYMPLHoxeYPz9/W6UChNDI7GcJu',
        ]);

        /*
        * Связь юзера и роли
        */

        $this->insert("auth_assignment", [
            "item_name" => "admin",
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
        echo "m171127_072157_insert_admin_to_users cannot be reverted.\n";

        return false;
    }
    */
}
