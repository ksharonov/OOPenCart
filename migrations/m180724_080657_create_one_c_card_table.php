<?php

use yii\db\Migration;

/**
 * Handles the creation of table `one_c_card`.
 */
class m180724_080657_create_one_c_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('one_c_card', [
            'id' => $this->primaryKey(),
            'number' => $this->integer()->comment('Номер карты'),
            'type' => $this->integer()->comment('Тип карты'),
            'discountValue' => $this->integer()->comment('Размер скидки'),
            'userId' => $this->integer()->comment('Пользователь')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('one_c_card');
    }
}
