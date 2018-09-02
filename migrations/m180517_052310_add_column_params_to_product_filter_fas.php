<?php

use yii\db\Migration;

/**
 * Class m180517_052310_add_column_params_to_product_filter_fas
 */
class m180517_052310_add_column_params_to_product_filter_fas extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_filter_fast', 'params', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_filter_fast', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180517_052310_add_column_params_to_product_filter_fas cannot be reverted.\n";

        return false;
    }
    */
}
