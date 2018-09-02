<?php

use yii\db\Migration;

/**
 * Class m171227_074628_add_name_to_product_filter
 */
class m171227_074628_add_name_to_product_filter extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_filter', 'name', $this->string(64)->comment('Имя фильтра'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_filter', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171227_074628_add_name_to_product_filter cannot be reverted.\n";

        return false;
    }
    */
}
