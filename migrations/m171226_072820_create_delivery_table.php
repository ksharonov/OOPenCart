<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m171226_072820_create_delivery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название способа доставки'),
            'class' => $this->string(128)->comment('Класс виджета'),
            'status' => $this->integer()->comment('Статус')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('delivery');
    }
}
