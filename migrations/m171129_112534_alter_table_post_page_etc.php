<?php

use yii\db\Migration;

/**
 * Class m171129_112534_alter_table_post_page_etc
 */
class m171129_112534_alter_table_post_page_etc extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('page', 'dtCreate', $this->timestamp());
        $this->alterColumn('page', 'dtUpdate', $this->timestamp());

        $this->alterColumn('post', 'dtCreate', $this->timestamp());
        $this->alterColumn('post', 'dtUpdate', $this->timestamp());

        $this->alterColumn('product', 'dtCreate', $this->timestamp());
        $this->alterColumn('product', 'dtUpdate', $this->timestamp());

        $this->alterColumn('product_category', 'dtCreate', $this->timestamp());
        $this->alterColumn('product_category', 'dtUpdate', $this->timestamp());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('page', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));
        $this->alterColumn('page', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

        $this->alterColumn('post', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));
        $this->alterColumn('post', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

        $this->alterColumn('product', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));
        $this->alterColumn('product', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

        $this->alterColumn('product_category', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));
        $this->alterColumn('product_category', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171129_112534_alter_table_post_page_etc cannot be reverted.\n";

        return false;
    }
    */
}
