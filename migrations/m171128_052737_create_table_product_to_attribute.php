<?php

use yii\db\Migration;

/**
 * Class m171128_052737_create_table_product_to_attribute
 */
class m171128_052737_create_table_product_to_attribute extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_to_attribute', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull(),
            'attributeId' => $this->integer()->notNull(),
            'attrValue' => $this->char(255)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->$this->dropTable('product_to_attribute');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_052737_create_table_product_to_attribute cannot be reverted.\n";

        return false;
    }
    */
}
