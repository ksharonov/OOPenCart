<?php

use yii\db\Migration;

/**
 * Class m180227_103536_add_seo_table_indexes
 */
class m180227_103536_add_seo_table_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('relModel_idx', 'seo', 'relModel');
        $this->createIndex('relModelId_idx', 'seo', 'relModelId');
        $this->createIndex('relModelId_relModel_idx', 'seo', ['relModelId', 'relModel']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('relModel_idx', 'seo');
        $this->dropIndex('relModelId_idx', 'seo');
        $this->dropIndex('relModelId_relModel_idx', 'seo');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_103536_add_seo_table_indexes cannot be reverted.\n";

        return false;
    }
    */
}
