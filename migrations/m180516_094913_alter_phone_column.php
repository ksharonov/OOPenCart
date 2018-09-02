<?php

use yii\db\Migration;

/**
 * Class m180516_094913_alter_phone_column
 */
class m180516_094913_alter_phone_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('lexema_card','phone', $this->bigInteger());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('lexema_card','phone', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_094913_alter_phone_column cannot be reverted.\n";

        return false;
    }
    */
}
