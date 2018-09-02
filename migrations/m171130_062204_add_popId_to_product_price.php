<?php

use yii\db\Migration;

/**
 * Class m171130_062204_add_popId_to_product_price
 */
class m171130_062204_add_popId_to_product_price extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_price', 'popId', $this->integer()->comment('id таблицы product_option_param'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_price', 'popId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171130_062204_add_popId_to_product_price cannot be reverted.\n";

        return false;
    }
    */
}
