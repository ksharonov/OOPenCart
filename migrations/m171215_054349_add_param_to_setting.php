<?php

use yii\db\Migration;

/**
 * Class m171215_054349_add_param_to_setting
 */
class m171215_054349_add_param_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'SITE.URL',
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
        echo "m171215_054349_add_param_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
