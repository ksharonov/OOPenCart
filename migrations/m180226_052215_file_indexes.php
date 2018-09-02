<?php

use yii\db\Migration;

/**
 * Class m180226_052215_file_indexes
 */
class m180226_052215_file_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('relModel_idx', 'file', 'relModel');
        $this->createIndex('relModelId_idx', 'file', 'relModelId');
        $this->createIndex('status_idx', 'file', 'status');
        $this->createIndex('type_idx', 'file', 'type');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('relModel_idx', 'file');
        $this->dropIndex('relModelId_idx', 'file');
        $this->dropIndex('status_idx', 'file');
        $this->dropIndex('type_idx', 'file');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180226_052215_file_indexes cannot be reverted.\n";

        return false;
    }
    */
}
