<?php

use yii\db\Migration;

/**
 * Class m180426_052551_add_column_params_to_file
 */
class m180426_052551_add_column_params_to_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('file', 'params', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('file', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180426_052551_add_column_params_to_file cannot be reverted.\n";

        return false;
    }
    */
}
