<?php

use yii\db\Migration;

/**
 * Class m180404_063848_add_params_to_order
 */
class m180404_063848_add_params_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'params', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180404_063848_add_params_to_order cannot be reverted.\n";

        return false;
    }
    */
}
