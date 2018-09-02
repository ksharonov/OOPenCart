<?php

use yii\db\Migration;

/**
 * Class m180403_094621_drop_is_columns_in_product
 */
class m180403_094621_drop_is_columns_in_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('product', 'isBest');
        $this->dropColumn('product', 'isNew');
        $this->dropColumn('product', 'isDiscount');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('product', 'isBest', $this->integer());
        $this->addColumn('product', 'isNew', $this->integer());
        $this->addColumn('product', 'isDiscount', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180403_094621_drop_is_columns_in_product cannot be reverted.\n";

        return false;
    }
    */
}
