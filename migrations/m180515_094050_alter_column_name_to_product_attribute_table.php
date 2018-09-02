<?php

use yii\db\Migration;

/**
 * Class m180515_094050_alter_column_name_to_product_attribute_table
 */
class m180515_094050_alter_column_name_to_product_attribute_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('product_attribute', 'name', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180515_094050_alter_column_name_to_product_attribute_table cannot be reverted.\n";
        $this->alterColumn('product_attribute', 'name', $this->string(64));
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180515_094050_alter_column_name_to_product_attribute_table cannot be reverted.\n";

        return false;
    }
    */
}
