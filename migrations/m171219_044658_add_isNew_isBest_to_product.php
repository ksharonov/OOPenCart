<?php

use yii\db\Migration;

/**
 * Class m171219_044658_add_isNew_isBest_to_product
 */
class m171219_044658_add_isNew_isBest_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'isBest', $this->boolean()->comment('Лучшее предложение'));
        $this->addColumn('product', 'isNew', $this->boolean()->comment('Новинка'));
        $this->addColumn('product', 'isDiscount', $this->boolean()->comment('Акция'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'isBest');
        $this->dropColumn('product', 'isNew');
        $this->dropColumn('product', 'isDiscount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171219_044658_add_isNew_isBest_to_product cannot be reverted.\n";

        return false;
    }
    */
}
