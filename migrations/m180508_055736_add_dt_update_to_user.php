<?php

use yii\db\Migration;

/**
 * Class m180508_055736_add_dt_update_to_user
 */
class m180508_055736_add_dt_update_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user', 'dtUpdate', $this->timestamp());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'dtUpdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180508_055736_add_dt_update_to_user cannot be reverted.\n";

        return false;
    }
    */
}
