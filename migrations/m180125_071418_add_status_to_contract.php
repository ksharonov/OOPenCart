<?php

use yii\db\Migration;

/**
 * Class m180125_071418_add_status_to_contract
 */
class m180125_071418_add_status_to_contract extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('contract', 'status', $this->integer()->comment('Статус'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('contract', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180125_071418_add_status_to_contract cannot be reverted.\n";

        return false;
    }
    */
}
