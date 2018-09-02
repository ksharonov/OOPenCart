<?php

use yii\db\Migration;

/**
 * Class m171206_095146_alter_table_product_to_product_option_value
 */
class m171206_095146_alter_table_product_to_product_option_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('product_to_option_value', 'productValueId', 'optionValueId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('product_to_option_value', 'optionValueId', 'productValueId');

        return true;
    }
}
