<?php

use yii\db\Migration;

/**
 * Class m171218_055505_add_manyAmount_to_product_and_category
 */
class m171218_055505_add_manyAmount_to_product_and_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'manyAmount', $this->integer()->comment('Кол-во для "Много"'));
        $this->addColumn('product_category', 'manyAmount', $this->integer()->comment('Кол-во для "Много"'));

        $this->createIndex('product-many-amount', 'product', 'manyAmount');
        $this->createIndex('product-cat-many-amount', 'product_category', 'manyAmount');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'manyAmount');
        $this->dropColumn('product_category', 'manyAmount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171218_055505_add_manyAmount_to_product_and_category cannot be reverted.\n";

        return false;
    }
    */
}
