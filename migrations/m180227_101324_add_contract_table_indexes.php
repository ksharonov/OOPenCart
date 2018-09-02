<?php

use yii\db\Migration;

/**
 * Class m180227_101324_add_contract_table_indexes
 */
class m180227_101324_add_contract_table_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('clientId_status_id_idx', 'contract', ['clientId', 'status', 'id']);
        $this->createIndex('status_idx', 'contract', 'status');
        $this->createIndex('clientId_idx', 'contract', 'clientId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('clientId_status_id_idx', 'contract');
        $this->dropIndex('status_idx', 'contract');
        $this->dropIndex('clientId_idx', 'contract');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_101324_add_contract_table_indexes cannot be reverted.\n";

        return false;
    }
    */
}
