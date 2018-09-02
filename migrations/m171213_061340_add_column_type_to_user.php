<?php

use yii\db\Migration;

/**
 * Class m171213_061340_add_column_type_to_user
 */
class m171213_061340_add_column_type_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user', 'type', $this->integer());
        $this->createIndex('user-type', 'user', 'type');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171213_061340_add_column_type_to_user cannot be reverted.\n";

        return false;
    }
    */
}
