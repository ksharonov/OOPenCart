<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m171127_101709_create_table_pages
 */
class m171127_101709_create_table_pages extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('pages', [
            'id' => $this->primaryKey()->comment('id страницы'),
            'title' => $this->string(255)->comment('Заголовок'),
            'slug' => $this->string(255)->comment('Ссылка'),
            'content' => $this->text()->comment('Содержимое'),
            'dtUpdate' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'dtCreate' => $this->dateTime()->defaultValue(new Expression('NOW()')),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('pages');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171127_101709_create_table_pages cannot be reverted.\n";

        return false;
    }
    */
}
