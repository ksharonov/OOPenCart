<?php

use yii\db\Migration;

/**
 * Class m180427_041000_add_column_parent_id_to_storage
 */
class m180427_041000_add_column_parent_id_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage', 'parentId', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'parentId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_041000_add_column_parent_id_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
