<?php

use yii\db\Migration;

/**
 * Class m180419_065426_add_column_params_to_discount
 */
class m180419_065426_add_column_params_to_discount extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'params', $this->text()->comment('Параметры'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_065426_add_column_params_to_discount cannot be reverted.\n";

        return false;
    }
    */
}
