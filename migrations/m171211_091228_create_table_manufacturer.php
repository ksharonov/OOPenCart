<?php

use yii\db\Migration;

/**
 * Class m171211_091228_create_table_manufacturer
 */
class m171211_091228_create_table_manufacturer extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('manufacturer', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128),
            'image' => $this->string(256)
        ]);

        $this->createIndex('manufacturer-title', 'manufacturer', 'title');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('manufacturer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171211_091228_create_table_manufacturer cannot be reverted.\n";

        return false;
    }
    */
}
