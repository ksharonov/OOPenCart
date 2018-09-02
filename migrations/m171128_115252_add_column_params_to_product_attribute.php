<?php

use yii\db\Migration;

/**
 * Class m171128_115252_add_column_params_to_product_attribute
 */
class m171128_115252_add_column_params_to_product_attribute extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_attribute', 'params', 'json DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_attribute', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_115252_add_column_params_to_product_attribute cannot be reverted.\n";

        return false;
    }
    */
}
