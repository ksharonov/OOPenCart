<?php

use yii\db\Migration;

/**
 * Class m180418_122624_add_dtStart_column
 */
class m180418_122624_add_dtStart_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'dtStart', $this->timestamp()->comment('Дата начала действия'));
        $this->addColumn('discount', 'dtEnd', $this->timestamp()->comment('Дата окончания действия'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'dtStart');
        $this->dropColumn('discount', 'dtEnd');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180418_122624_add_dtStart_column cannot be reverted.\n";

        return false;
    }
    */
}
