<?php

use yii\db\Migration;

/**
 * Class m180207_070220_change_storage_dt_to_time
 */
class m180207_070220_change_storage_dt_to_time extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('storage', 'dtBeginWork');
        $this->dropColumn('storage', 'dtEndWork');

        $this->addColumn('storage', 'timeBeginWork', $this->time()->comment('Время начала работы'));
        $this->addColumn('storage', 'timeEndWork', $this->time()->comment('Время окончания работы'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'timeBeginWork');
        $this->dropColumn('storage', 'timeEndWork');

        $this->addColumn('storage', 'dtBeginWork', $this->timestamp()->comment('Время начала работы'));
        $this->addColumn('storage', 'dtEndWork', $this->timestamp()->comment('Время окончания работы'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180207_070220_change_storage_dt_to_time cannot be reverted.\n";

        return false;
    }
    */
}
