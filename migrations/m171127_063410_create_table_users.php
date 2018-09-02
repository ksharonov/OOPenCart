<?php

use yii\db\Migration;

/**
 * Class m171127_063410_create_table_users
 */
class m171127_063410_create_table_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        Yii::$app->runAction('migrate/up', [
            'migrationPath' => '@yii/rbac/migrations',
            'interactive' => false,
        ]);
        $this->createTable('users', [
            'uid' => $this->primaryKey()->comment('id пользователя'),
            'username' => $this->string(40)->comment('Логин'),
            'email' => $this->string(255)->comment('Почтовый адрес'),
            'password' => $this->string(255)->comment('Пароль'),
            'dtcreate' => $this->timestamp()->comment('Дата создания'),
            'access_token' => $this->string(50)->comment("Токен пользователя"),
            'authKey' => $this->string(50)->comment("Ключ авторизации"),
            'change_password_key' => $this->string(32)->comment("Ключ смены пароля")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('users');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171127_063410_create_table_users cannot be reverted.\n";

        return false;
    }
    */
}
