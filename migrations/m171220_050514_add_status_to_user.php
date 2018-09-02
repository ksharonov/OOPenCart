<?php

use yii\db\Migration;

/**
 * Class m171220_050514_add_status_to_user
 */
class m171220_050514_add_status_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user', 'status', $this->integer()->comment('Статус пользователя'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_050514_add_status_to_user cannot be reverted.\n";

        return false;
    }
    */
}
