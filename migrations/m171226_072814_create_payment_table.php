<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m171226_072814_create_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название метода оплаты'),
            'class' => $this->string(128)->comment('Класс виджета'),
            'status' => $this->integer()->comment('Статус')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('payment');
    }
}
