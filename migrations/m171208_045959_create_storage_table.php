<?php

use yii\db\Migration;

/**
 * Handles the creation of table `storage`.
 */
class m171208_045959_create_storage_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('storage', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->comment('Название'),
            'content' => $this->text()->comment('Содержимое'),
            'type' => $this->integer()->comment('Тип'),
            'status' => $this->integer()->comment('Статус')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('storage');
    }
}
