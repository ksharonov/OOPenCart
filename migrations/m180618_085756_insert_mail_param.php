<?php

use yii\db\Migration;

/**
 * Class m180618_085756_insert_mail_param
 */
class m180618_085756_insert_mail_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'MAIL.ADMIN',
            'setValue' => 'admin@example.com',
            'type' => 0,
            'title' => 'E-mail Администратора'
        ]);

        $this->insert('setting', [
            'setKey' => 'MAIL.CONTENT.MANAGER',
            'setValue' => 'admin@example.com',
            'type' => 0,
            'title' => 'E-mail Администратора'
        ]);

        $this->insert('setting', [
            'setKey' => 'MAIL.SALES.MANAGER',
            'setValue' => 'admin@example.com',
            'type' => 0,
            'title' => 'E-mail Менеджера по продажам'
        ]);

        $this->insert('setting', [
            'setKey' => 'MAIL.SALES.WHOLESALE.MANAGER',
            'setValue' => 'admin@example.com',
            'type' => 0,
            'title' => 'E-mail Менеджера по оптовым продажам'
        ]);

        $this->insert('setting', [
            'setKey' => 'MAIL.SALES.RETAIL.MANAGER',
            'setValue' => 'admin@example.com',
            'type' => 0,
            'title' => 'E-mail Менеджера по розничным продажам'
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
        echo "m180618_085756_insert_mail_param cannot be reverted.\n";

        return false;
    }
    */
}
