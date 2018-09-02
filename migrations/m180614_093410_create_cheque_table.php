<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cheque`.
 */
class m180614_093410_create_cheque_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cheque', [
            'id' => $this->primaryKey(),
            'requestId' => $this->string(32)->comment('Уникальный id запроса'),
            'url' => $this->string(256)->comment('URL-запроса'),
            'success' => $this->boolean()->comment('Успешность запроса')->defaultValue(false),
            'params' => $this->text()->comment('Содержимое'),
            'dtCreate' => $this->timestamp()->defaultValue(null)->null()->comment('Дата создания'),
            'dtUpdate' => $this->timestamp()->defaultValue(null)->null()->comment('Дата обновления')
        ]);

        $this->createIndex('cheque-request', 'cheque', 'requestId');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cheque');
    }
}
