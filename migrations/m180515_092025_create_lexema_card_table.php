<?php

use yii\db\Migration;

/**
 * Handles the creation of table `lexema_card`.
 */
class m180515_092025_create_lexema_card_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('lexema_card', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->comment('Пользователь'),
            'number' => $this->string(64)->comment('Номер карты'),
            'type' => $this->integer()->comment('Тип карты'),
            'bonuses' => $this->integer()->comment('Сумма бонусов'),
            'amountPurchases' => $this->integer()->comment('Сумма покупок')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('lexema_card');
    }
}
