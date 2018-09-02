<?php

use yii\db\Migration;

/**
 * Class m180516_104157_alter_column_phone
 */
class m180516_104157_alter_column_phone extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('lexema_card', 'phone', $this->string(128));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('lexema_card', 'phone', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_104157_alter_column_phone cannot be reverted.\n";

        return false;
    }
    */
}
