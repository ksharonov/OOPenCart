<?php

use yii\db\Migration;

/**
 * Class m180111_054007_drop_column_name_from_product_filter
 */
class m180111_054007_drop_column_name_from_product_filter extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180111_054007_drop_column_name_from_product_filter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180111_054007_drop_column_name_from_product_filter cannot be reverted.\n";

        return false;
    }
    */
}
