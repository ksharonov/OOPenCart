<?php

use yii\db\Migration;

/**
 * Class m171128_050127_alter_table_product_category
 */
class m171128_050127_alter_table_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'cost', $this->integer()->comment('Цена'));
        $this->addColumn('product_category', 'parentId', $this->integer()->comment('Родительская категория'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'cost');
        $this->dropColumn('product_category', 'parent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_050127_alter_table_product_category cannot be reverted.\n";

        return false;
    }
    */
}
