<?php

use yii\db\Migration;

/**
 * Class m180724_074549_add_discount_system_param_to_setting
 */
class m180724_074549_add_discount_system_param_to_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'SITE.USE.DISCOUNT.SYSTEM',
            'setValue' => 0,
            'type' => 0,
            'title' => 'Использовать систему дисконтных карт'
        ]);
    }

    /**
     * {@inheritdoc}
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
        echo "m180724_074549_add_discount_system_param_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
