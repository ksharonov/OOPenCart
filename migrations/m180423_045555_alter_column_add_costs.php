<?php

use yii\db\Migration;

/**
 * Class m180423_045555_alter_column_add_costs
 */
class m180423_045555_alter_column_add_costs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'discountData', $this->text());
        $this->addCommentOnColumn('order', 'discountData', 'Скидочные данные');
        $this->addCommentOnColumn('order', 'addCosts', 'Доп.затраты');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('order', 'discountData', 'json DEFAULT NULL');
        $this->addCommentOnColumn('order', 'discountData', 'Скидочные данные');
        $this->addCommentOnColumn('order', 'addCosts', 'Доп.затраты');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180423_045555_alter_column_add_costs cannot be reverted.\n";

        return false;
    }
    */
}
