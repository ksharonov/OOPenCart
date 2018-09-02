<?php

use yii\db\Migration;

/**
 * Class m171128_040947_rename_table_pages_to_page
 */
class m171128_040947_rename_table_pages_to_page extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('pages', 'page');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameTable('page', 'pages');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_040947_rename_table_pages_to_page cannot be reverted.\n";

        return false;
    }
    */
}
