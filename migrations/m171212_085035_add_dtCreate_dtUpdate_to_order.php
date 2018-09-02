<?php

use yii\db\Migration;

/**
 * Class m171212_085035_add_dtCreate_dtUpdate_to_order
 */
class m171212_085035_add_dtCreate_dtUpdate_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'dtCreate', $this->timestamp());
        $this->addColumn('order', 'dtUpdate', $this->timestamp());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'dtCreate');
        $this->dropColumn('order', 'dtUpdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171212_085035_add_dtCreate_dtUpdate_to_order cannot be reverted.\n";

        return false;
    }
    */
}
