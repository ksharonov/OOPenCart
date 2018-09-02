<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contract`.
 */
class m171215_073852_create_contract_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('contract', [
            'id' => $this->primaryKey(),
            'number' => $this->string(128)->comment('Номер договора'),
            'dtStart' => $this->timestamp()->comment('Дата начала договора'),
            'dtEnd' => $this->timestamp()->comment('Дата окончания договора'),
            'clientId' => $this->integer()->comment('Клиент')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('contract');
    }
}
