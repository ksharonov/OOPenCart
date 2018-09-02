<?php

use yii\db\Migration;
use \yii\db\Expression;

/**
 * Class m171127_095417_create_table_posts
 */
class m171127_095417_create_table_posts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey()->comment('id поста'),
            'title' => $this->string(255)->notNull()->comment('Заголовок'),
            'content' => $this->string()->comment('Контент'),
            'date' => $this->dateTime()->defaultValue(new Expression('NOW()'))
                ->comment('Дата создания'),
            'status' => $this->integer()->defaultValue(0)->comment('Статус'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('posts');
        return true;
    }
}
