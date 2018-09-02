<?php

use yii\db\Migration;

/**
 * Class m180417_044828_add_column_parent_id_to_extension
 */
class m180417_044828_add_column_parent_id_to_extension extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('extension', 'parentId', $this->integer()->comment('Родительский виджет'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('extension', 'parentId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180417_044828_add_column_parent_id_to_extension cannot be reverted.\n";

        return false;
    }
    */
}
