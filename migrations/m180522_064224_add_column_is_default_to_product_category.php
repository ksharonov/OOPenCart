<?php

use yii\db\Migration;

/**
 * Class m180522_064224_add_column_is_default_to_product_category
 */
class m180522_064224_add_column_is_default_to_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_category', 'isDefault', $this->boolean()->defaultValue(0)->comment('Стандартная категория'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_category', 'isDefault');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180522_064224_add_column_is_default_to_product_category cannot be reverted.\n";

        return false;
    }
    */
}
