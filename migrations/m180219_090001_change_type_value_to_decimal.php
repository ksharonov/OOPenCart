<?php

use yii\db\Migration;

/**
 * Class m180219_090001_change_type_value_to_decimal
 */
class m180219_090001_change_type_value_to_decimal extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('product_price', 'value', $this->decimal(9,2)->comment('Цена'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('product_price', 'value', $this->float()->comment('Цена'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180219_090001_change_type_value_to_decimal cannot be reverted.\n";

        return false;
    }
    */
}
