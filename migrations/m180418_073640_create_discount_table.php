<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount`.
 */
class m180418_073640_create_discount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('discount', [
            'id' => $this->primaryKey(),
            'title' => $this->string(256)->comment('Название скидки'),
            'relModel' => $this->integer()->comment('Связанная модель'),
            'relModelId' => $this->integer()->comment('id связанной модели'),
            'type' => $this->integer()->comment('Тип скидки'),
            'status' => $this->integer()->comment('Статус'),
            'value' => $this->integer()->comment('Значение')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('discount');
    }
}
