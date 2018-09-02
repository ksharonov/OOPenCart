<?php

use yii\db\Migration;

/**
 * Class m180126_102713_add_state_and_dtReceipt
 */
class m180126_102713_add_state_and_dtReceipt extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage_balance', 'state', $this->integer()->comment('Состояние товаров'));
        $this->addColumn('storage_balance', 'dtReceipt', $this->timestamp()->comment('Дата поступления в наличие'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage_balance', 'state');
        $this->dropColumn('storage_balance', 'dtReceipt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180126_102713_add_state_and_dtReceipt cannot be reverted.\n";

        return false;
    }
    */
}
