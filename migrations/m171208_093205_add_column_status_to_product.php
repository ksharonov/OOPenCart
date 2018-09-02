<?php

use yii\db\Migration;

/**
 * Class m171208_093205_add_column_status_to_product
 */
class m171208_093205_add_column_status_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'status', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_093205_add_column_status_to_product cannot be reverted.\n";

        return false;
    }
    */
}
