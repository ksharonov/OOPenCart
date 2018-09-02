<?php

use yii\db\Migration;

/**
 * Class m171227_103112_rename_columns_in_table_outer_rel
 */
class m171227_103112_rename_columns_in_table_outer_rel extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('outer_rel', 'title', 'guid');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('outer_rel', 'guid', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171227_103112_rename_columns_in_table_outer_rel cannot be reverted.\n";

        return false;
    }
    */
}
