<?php

use yii\db\Migration;

/**
 * Class m171128_054513_alter_table_product
 */
class m171128_054513_alter_table_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('product', 'cost');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('product', 'cost', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_054513_alter_table_product cannot be reverted.\n";

        return false;
    }
    */
}
