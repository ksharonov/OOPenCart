<?php

use yii\db\Migration;

/**
 * Class m180419_060125_alter_column_discountValue
 */
class m180419_060125_alter_column_discountValue extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('order_content', 'discountValue', $this->decimal(9, 2)->comment('Размер скидки на единицу товара'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('order_content', 'discountValue', $this->double()->comment('Размер скидки на единицу товара'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_060125_alter_column_discountValue cannot be reverted.\n";

        return false;
    }
    */
}
