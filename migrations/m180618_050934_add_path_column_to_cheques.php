<?php

use yii\db\Migration;

/**
 * Class m180618_050934_add_path_column_to_cheques
 */
class m180618_050934_add_path_column_to_cheques extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('cheque', 'path', $this->string(128)->comment('Путь запроса'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('cheque', 'path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180618_050934_add_path_column_to_cheques cannot be reverted.\n";

        return false;
    }
    */
}
