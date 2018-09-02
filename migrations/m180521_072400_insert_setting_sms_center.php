<?php

use yii\db\Migration;

/**
 * Class m180521_072400_insert_setting_sms_center
 */
class m180521_072400_insert_setting_sms_center extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'SMS: Url для SMS-сервиса',
            'setKey' => 'SMS.SERVICE.URL',
            'setValue' => 'http://smsc.ru/sys/send.php'
        ]);

        $this->insert('setting', [
            'title' => 'SMS: Login для SMS-сервиса',
            'setKey' => 'SMS.SERVICE.LOGIN',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'title' => 'SMS: Пароль для SMS-сервиса',
            'setKey' => 'SMS.SERVICE.PASSWORD',
            'setValue' => ''
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
        echo "m180521_072400_insert_setting_sms_center cannot be reverted.\n";

        return false;
    }
    */
}
