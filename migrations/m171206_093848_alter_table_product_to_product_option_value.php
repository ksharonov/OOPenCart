<?php

use yii\db\Migration;

/**
 * Class m171206_093848_alter_table_product_to_product_option_value
 */
class m171206_093848_alter_table_product_to_product_option_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_to_option_value', 'optionId', $this->integer()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_to_option_value', 'optionId');

        return true;
    }
}
