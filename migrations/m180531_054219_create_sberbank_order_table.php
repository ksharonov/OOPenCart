<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sberbank_order`.
 */
class m180531_054219_create_sberbank_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sberbank_order', [
            'id' => $this->primaryKey(),
            'orderId' => $this->integer(),
            'sberbankOrderId' => $this->string(128),
            'formUrl' => $this->string(256)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('sberbank_order');
    }
}
