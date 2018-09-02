<?php

use yii\db\Migration;

/**
 * Class m171207_060234_create_indexes_for_tables
 */
class m171207_060234_create_indexes_for_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('product-option-param-to-product', 'product_option_param', 'productId');

        $this->createIndex('product-option-title', 'product_option', 'title');
        $this->createIndex('product-option-value-value', 'product_option_value', 'value');
        $this->createIndex('product-option-value-to-option', 'product_option_value', 'optionId');

        $this->createIndex('product-to-option-value', 'product_to_option_value', ['productId', 'optionValueId']);
        $this->createIndex('product-option-value-to-option-2', 'product_to_option_value', ['optionValueId', 'optionId']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('product-option-param-to-product', 'product_option_param');

        $this->dropIndex('product-option-title', 'product_option');
        $this->dropIndex('product-option-value-value', 'product_option_value');
        $this->dropIndex('product-option-value-to-option', 'product_option_value');

        $this->dropIndex('product-to-option-value', 'product_to_option_value');
        $this->dropIndex('product-option-value-to-option-2', 'product_to_option_value');

        return true;
    }
}
