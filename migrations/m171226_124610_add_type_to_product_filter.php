<?php

use yii\db\Migration;

/**
 * Class m171226_124610_add_type_to_product_filter
 */
class m171226_124610_add_type_to_product_filter extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_filter', 'type', $this->integer()->comment('Тип фильтра'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_filter', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171226_124610_add_type_to_product_filter cannot be reverted.\n";

        return false;
    }
    */
}
