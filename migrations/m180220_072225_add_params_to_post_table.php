<?php

use yii\db\Migration;

/**
 * Class m180220_072225_add_params_to_post_table
 */
class m180220_072225_add_params_to_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post', 'params', $this->text()->comment('Параметры'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180220_072225_add_params_to_post_table cannot be reverted.\n";

        return false;
    }
    */
}
