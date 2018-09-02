<?php

use yii\db\Migration;

/**
 * Class m180419_062741_add_column_addCosts
 */
class m180419_062741_add_column_addCosts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'addCosts', $this->text()->comment('Дополнительные затраты'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'addCosts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_062741_add_column_addCosts cannot be reverted.\n";

        return false;
    }
    */
}
