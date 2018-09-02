<?php

use yii\db\Migration;

/**
 * Class m180619_101752_add_column_resp_content_to_cheque
 */
class m180619_101752_add_column_resp_content_to_cheque extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('cheque', 'responseContent', $this->text()->comment('Результат запроса'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('cheque', 'responseContent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180619_101752_add_column_resp_content_to_cheque cannot be reverted.\n";

        return false;
    }
    */
}
