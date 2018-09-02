<?php

use yii\db\Migration;

/**
 * Class m180528_065456_change_type_phone_sms
 */
class m180528_065456_change_type_phone_sms extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('sms', 'phone', $this->string(32));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('sms', 'phone', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180528_065456_change_type_phone_sms cannot be reverted.\n";

        return false;
    }
    */
}
