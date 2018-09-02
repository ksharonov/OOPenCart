<?php

use yii\db\Migration;

/**
 * Class m180216_100139_outer_rel_indexes
 */
class m180216_100139_outer_rel_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('guid_idx', 'outer_rel', 'guid');
        $this->createIndex('relModel_idx', 'outer_rel', 'relModel');
        $this->createIndex('relModelId_idx', 'outer_rel', 'relModelId');
        $this->createIndex('relModel_guid_unique', 'outer_rel', ['relModel', 'guid'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('guid_idx', 'outer_rel');
        $this->dropIndex('relModel_idx', 'outer_rel');
        $this->dropIndex('relModelId_idx', 'outer_rel');
        $this->dropIndex('relModel_guid_unique', 'outer_rel');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180216_100139_outer_rel_indexes cannot be reverted.\n";

        return false;
    }
    */
}
