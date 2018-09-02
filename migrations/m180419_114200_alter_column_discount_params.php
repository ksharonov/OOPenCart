<?php

use yii\db\Migration;

/**
 * Class m180419_114200_alter_column_discount_params
 */
class m180419_114200_alter_column_discount_params extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('discount', 'params', 'json DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_114200_alter_column_discount_params cannot be reverted.\n";

        return false;
    }
    */
}
