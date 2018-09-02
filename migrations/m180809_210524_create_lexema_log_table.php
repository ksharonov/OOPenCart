<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lexema_log`.
 */
class m180809_210524_create_lexema_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('lexema_log', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->comment('Вид операции'),
            'dtCreate' => $this->timestamp()->defaultValue(null)->null()->comment('Дата создания'),
            'dtUpdate' => $this->timestamp()->defaultValue(null)->null()->comment('Дата обновления'),
            'count' => $this->integer()->comment('Количество загруженных объектов'),
            'params' => $this->text()->comment('Техническая информация')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('lexema_log');
    }
}
