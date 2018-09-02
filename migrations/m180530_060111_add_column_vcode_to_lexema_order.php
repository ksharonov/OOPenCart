<?php

use yii\db\Migration;

/**
 * Class m180530_060111_add_column_vcode_to_lexema_order
 */
class m180530_060111_add_column_vcode_to_lexema_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
    	$this->addColumn('lexema_order', 'vcode', $this->integer(11));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('lexema_order', 'vcode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180530_060111_add_column_vcode_to_lexema_order cannot be reverted.\n";

        return false;
    }
    */
}
