<?php

use yii\db\Migration;

/**
 * Class m180402_064908_add_phone_to_storage
 */
class m180402_064908_add_phone_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage', 'phone', $this->string(128));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180402_064908_add_phone_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
