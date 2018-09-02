<?php

use yii\db\Migration;

/**
 * Class m171206_073334_add_table_product_to_product_option_value
 */
class m171206_073334_add_table_product_to_product_option_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_to_option_value', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull()->comment('Ид товара'),
            'productValueId' => $this->integer()->notNull()->comment('Ид значения опции'),
        ]);

        $this->dropTable('product_to_option');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_to_option_value');

        $this->createTable('product_to_option', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull()->comment('Ид товара'),
            'optionId' => $this->integer()->notNull()->comment('Ид опции'),
        ]);

        return true;
    }
}
