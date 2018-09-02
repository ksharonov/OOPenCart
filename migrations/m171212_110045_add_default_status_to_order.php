<?php

use yii\db\Migration;

/**
 * Class m171212_110045_add_default_status_to_order
 */
class m171212_110045_add_default_status_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'DEFAULT.ORDER.ID',
            'setValue' => null
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
        echo "m171212_110045_add_default_status_to_order cannot be reverted.\n";

        return false;
    }
    */
}
