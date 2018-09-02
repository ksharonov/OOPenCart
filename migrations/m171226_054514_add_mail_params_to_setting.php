<?php

use yii\db\Migration;

/**
 * Class m171226_054514_add_mail_params_to_setting
 */
class m171226_054514_add_mail_params_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'SMTP.CLASS',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'setKey' => 'SMTP.HOST',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'setKey' => 'SMTP.LOGIN',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'setKey' => 'SMTP.PASSWORD',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'setKey' => 'SMTP.PORT',
            'setValue' => ''
        ]);

        $this->insert('setting', [
            'setKey' => 'SMTP.ENCRYPTION',
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
        echo "m171226_054514_add_mail_params_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
