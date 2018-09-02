<?php

use yii\db\Migration;

/**
 * Class m171208_060244_add_column_popId_to_storage
 */
class m171208_060244_add_column_popId_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage_balance', 'popId', $this->integer()->comment('Набор'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage_balance', 'popId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_060244_add_column_popId_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
