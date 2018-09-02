<?php

use yii\db\Migration;

/**
 * Class m180112_121958_add_status_type_to_order_status
 */
class m180112_121958_add_status_type_to_order_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order_status', 'type', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order_status', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180112_121958_add_status_type_to_order_status cannot be reverted.\n";

        return false;
    }
    */
}
