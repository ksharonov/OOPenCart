<?php

use yii\db\Migration;

/**
 * Class m180523_105033_add_column_discountValue
 */
class m180523_105033_add_column_discountValue extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('lexema_card', 'discountValue', $this->float()->defaultValue(0)->comment('Размер скидки'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('lexema_card', 'discountValue');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_105033_add_column_discountValue cannot be reverted.\n";

        return false;
    }
    */
}
