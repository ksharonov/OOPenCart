<?php

use yii\db\Migration;

/**
 * Class m171208_073011_create_table_product_unit
 */
class m171208_073011_create_table_product_unit extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_unit', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer(),
            'unitId' => $this->integer(),
            'rate' => $this->integer()
        ]);

        $this->createIndex('unit-title', 'unit', 'title');

        $this->createIndex('product-unit-product', 'product_unit', 'productId');
        $this->createIndex('product-unit-unit', 'product_unit', 'unitId');

        $this->truncateTable('unit');

        $this->insert('unit', [
                'title' => 'шт.'
            ]
        );

        $this->insert('setting', [
            'setKey' => 'DEFAULT.UNIT.ID',
            'setValue' => 1
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_unit');

        $this->dropIndex('unit-title', 'unit');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_073011_create_table_product_unit cannot be reverted.\n";

        return false;
    }
    */
}
