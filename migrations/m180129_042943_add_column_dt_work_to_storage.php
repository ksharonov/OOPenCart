<?php

use yii\db\Migration;

/**
 * Class m180129_042943_add_column_dt_work_to_storage
 */
class m180129_042943_add_column_dt_work_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage', 'dtBeginWork', $this->timestamp()->comment('Время начала работы'));
        $this->addColumn('storage', 'dtEndWork', $this->timestamp()->comment('Время окончания работы'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'dtBeginWork');
        $this->dropColumn('storage', 'dtEndWork');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180129_042943_add_column_dt_work_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
