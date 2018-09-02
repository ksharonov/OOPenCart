<?php

use yii\db\Migration;

/**
 * Class m180601_063308_add_column_size_to_file_table
 */
class m180601_063308_add_column_size_to_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('file', 'size', $this->integer()->defaultValue(0)->comment('Размер файла'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('file', 'size');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180601_063308_add_column_size_to_file_table cannot be reverted.\n";

        return false;
    }
    */
}
