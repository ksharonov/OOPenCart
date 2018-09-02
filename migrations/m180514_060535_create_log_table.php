<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m180514_060535_create_log_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('log', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->comment('Содержимое'),
            'dtCreate' => $this->timestamp()->comment('Дата создания'),
            'dtUpdate' => $this->timestamp()->comment('Дата обновления')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('log');
    }
}
