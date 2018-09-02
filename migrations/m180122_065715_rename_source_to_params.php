<?php

use yii\db\Migration;

/**
 * Class m180122_065715_rename_source_to_params
 */
class m180122_065715_rename_source_to_params extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('setting','source', 'params');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('setting','params', 'source');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180122_065715_rename_source_to_params cannot be reverted.\n";

        return false;
    }
    */
}
