<?php

use yii\db\Migration;

/**
 * Class m180419_094112_add_column_priority_to_discount
 */
class m180419_094112_add_column_priority_to_discount extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'priority', $this->integer()->comment('Приоритет'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'priority');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_094112_add_column_priority_to_discount cannot be reverted.\n";

        return false;
    }
    */
}
