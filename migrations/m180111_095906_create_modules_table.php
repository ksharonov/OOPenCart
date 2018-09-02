<?php

use yii\db\Migration;

/**
 * Handles the creation of table `modules`.
 */
class m180111_095906_create_modules_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropTable('payment');
        $this->dropTable('delivery');

        $this->createTable('extension', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название модуля'),
            'name' => $this->string(128)->comment('Имя виджета'),
            'class' => $this->string(256)->comment('Класс модуля'),
            'type' => $this->integer()->comment('Тип'),
            'status' => $this->integer()->comment('Статус')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('extension');
    }
}
