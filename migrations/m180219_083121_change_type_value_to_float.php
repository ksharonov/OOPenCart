<?php

use yii\db\Migration;

/**
 * Class m180219_083121_change_type_value_to_float
 */
class m180219_083121_change_type_value_to_float extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('product_price', 'value', $this->float()->comment('Цена'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('product_price', 'value', $this->integer()->comment('Цена'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180219_083121_change_type_value_to_float cannot be reverted.\n";

        return false;
    }
    */
}
