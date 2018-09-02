<?php

use yii\db\Migration;

/**
 * Class m171208_070631_create_table_units
 */
class m171208_070631_create_table_units extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('unit', [
            'id' => $this->primaryKey(),
            'title' => $this->string(64)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('unit');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171208_070631_create_table_units cannot be reverted.\n";

        return false;
    }
    */
}
