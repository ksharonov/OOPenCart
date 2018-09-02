<?php

use yii\db\Migration;

/**
 * Class m180116_042747_add_params_to_extensions
 */
class m180116_042747_add_params_to_extensions extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('extension', 'params', $this->text()->comment('Параметры виджета'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('extension', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180116_042747_add_params_to_extensions cannot be reverted.\n";

        return false;
    }
    */
}
