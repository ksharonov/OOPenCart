<?php

use yii\db\Migration;

/**
 * Class m180306_095542_change_type_quantity_on_storage_balance_to_decimal
 */
class m180306_095542_change_type_quantity_on_storage_balance_to_decimal extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('storage_balance', 'quantity', $this->decimal(9,3)->comment('Количество'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('storage_balance', 'quantity', $this->integer()->comment('Количество'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180306_095542_change_type_quantity_on_storage_balance_to_decimal cannot be reverted.\n";

        return false;
    }
    */
}
