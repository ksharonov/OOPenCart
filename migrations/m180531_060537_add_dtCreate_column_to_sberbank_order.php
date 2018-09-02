<?php

use yii\db\Migration;

/**
 * Class m180531_060537_add_dtCreate_column_to_sberbank_order
 */
class m180531_060537_add_dtCreate_column_to_sberbank_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('sberbank_order', 'dtCreate', $this->timestamp()->defaultValue(null)->comment('Дата создания'));
        $this->addColumn('sberbank_order', 'dtUpdate', $this->timestamp()->defaultValue(null)->comment('Дата обновления'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('sberbank_order', 'dtCreate');
        $this->dropColumn('sberbank_order', 'dtupdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180531_060537_add_dtCreate_column_to_sberbank_order cannot be reverted.\n";

        return false;
    }
    */
}
