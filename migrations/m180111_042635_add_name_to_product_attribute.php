<?php

use yii\db\Migration;

/**
 * Class m180111_042635_add_name_to_product_attribute
 */
class m180111_042635_add_name_to_product_attribute extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_attribute', 'name', $this->string(64)->comment('Имя атрибута'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_attribute', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180111_042635_add_name_to_product_attribute cannot be reverted.\n";

        return false;
    }
    */
}
