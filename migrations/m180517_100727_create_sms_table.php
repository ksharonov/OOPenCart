<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sms`.
 */
class m180517_100727_create_sms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sms', [
            'id' => $this->primaryKey(),
            'code' => $this->string(64)->comment('Код СМС'),
            'success' => $this->boolean()->comment('Подтверждено'),
            'type' => $this->integer()->comment('Тип сообщения'),
            'phone' => $this->integer()->comment('Телефон'),
            'attempts' => $this->integer()->comment('Количество попыток')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('sms');
    }
}
