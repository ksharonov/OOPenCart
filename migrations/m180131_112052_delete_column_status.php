<?php

use yii\db\Migration;

/**
 * Class m180131_112052_delete_column_status
 */
class m180131_112052_delete_column_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('order', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('order', 'status', $this->integer()->comment('Статус'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180131_112052_delete_column_status cannot be reverted.\n";

        return false;
    }
    */
}
