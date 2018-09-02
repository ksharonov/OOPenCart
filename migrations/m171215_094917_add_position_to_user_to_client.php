<?php

use yii\db\Migration;

/**
 * Class m171215_094917_add_position_to_user_to_client
 */
class m171215_094917_add_position_to_user_to_client extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_to_client', 'position', $this->integer()->comment('Должность'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_to_client', 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171215_094917_add_position_to_user_to_client cannot be reverted.\n";

        return false;
    }
    */
}
